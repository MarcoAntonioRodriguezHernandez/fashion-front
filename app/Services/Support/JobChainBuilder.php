<?php

namespace App\Services\Support;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\PendingChain;
use Illuminate\Support\Facades\Bus;

class JobChainBuilder
{
    /**
     * @var array
     */
    protected array $jobs = [];

    public static function make(array $jobs = []): self
    {
        return new static($jobs);
    }

    public function __construct(array $jobs = [])
    {
        $this->jobs = $jobs;
    }

    /**
     * Create a new chain with the given jobs.
     */
    public function build(): PendingChain
    {
        if (empty($this->jobs)) {
            throw new Exception('No jobs to build');
        }

        return Bus::chain($this->jobs);
    }

    /**
     * Dispatch the built chain.
     */
    public function dispatch(): void
    {
        $this->build()->dispatch();
    }

    public function when($condition, $callback): self
    {
        if ($condition)
            $callback($this);

        return $this;
    }

    /**
     * Prepend a job to the chain.
     */
    public function prependJob(ShouldQueue $job): self
    {
        return $this->prependJobs([$job]);
    }

    /**
     * Prepend jobs to the chain.
     */
    public function prependJobs(array $job): self
    {
        $this->jobs = array_merge($job, $this->jobs);

        return $this;
    }

    /**
     * Append a job to the chain.
     */
    public function appendJob(ShouldQueue $job): self
    {
        return $this->appendJobs([$job]);
    }

    /**
     * Append jobs to the chain.
     */
    public function appendJobs(array $job): self
    {
        $this->jobs = array_merge($this->jobs, $job);

        return $this;
    }
}
