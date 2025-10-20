<?php

namespace App\Services\Common\Ticket;

use App\Models\User;
use Mpdf\Mpdf;

class TicketBuilder
{
    /**
     * @var string
     */
    private string $fileName;
    /**
     * @var string
     */
    private string $viewName;
    /**
     * @var int
     */
    private int $margins = 4;
    /**
     * @var int
     */
    private int $width = 80;
    /**
     * @var User
     */
    private User $client;
    /**
     * @var array
     */
    private array $data;
    /**
     * @var array
     */
    private array $styleSheets;
    /**
     * @var string
     */
    private string $title;
    /**
     * @var string|null
     */
    private ?string $notes;

    /**
     * Create a new instance of the class.
     * 
     * @param User $client
     * @param string $viewName
     * 
     * @return static
     */
    public static function make(User $client, string $viewName): self
    {
        return new static($client, $viewName);
    }

    public function __construct(User $client, string $viewName)
    {
        $this->title(__('Ticket'));
        $this->fileName(__('Ticket'));
        $this->viewName = $viewName;
        $this->client = $client;
        $this->data = [];
        $this->styleSheets = [];
        $this->notes = null;
    }

    /**
     * Convert the ticket to data formatted to view usage.
     */
    public function toViewData(): array
    {
        return [
            'ticket' => (object) [
                'title' => $this->title,
                'notes' => $this->notes,
            ],
            'client' => $this->client,
            ...$this->data,
        ];
    }

    /**
     * Convert the ticket to HTML content.
     */
    public function toHtmlContent(): string
    {
        return view($this->viewName, $this->toViewData())->render();
    }

    /**
     * Convert the ticket to PDF content.
     */
    public function toPdfContent(): string
    {
        $content = $this->toHtmlContent();

        $file = new Mpdf([
            'format' => [$this->width, 3000],
            'default_font_size' => 8,
            'default_font' => 'sans-serif',
            'margin_left' => $this->margins,
            'margin_right' => $this->margins,
            'margin_top' => $this->margins * 2,
            'margin_bottom' => $this->margins * 1,
            'defaultCssFile' => 'assets/css/ticket.css',
            'tempDir' => storage_path('tempdir'),
        ]);

        $file->AddPage('P', newformat: [$this->width, $file->_getHtmlHeight($content) + ($file->tMargin + $file->bMargin)]);

        foreach ($this->styleSheets as $styleSheet) {
            $stylesheet = file_get_contents($styleSheet);
            $file->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        }

        $file->WriteHTML($content);

        return $file->Output($this->fileName . '.pdf', 'I');
    }

    /**
     * Set the file name of the ticket file.
     */
    public function fileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Set the title of the ticket.
     */
    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the notes of the ticket.
     */
    public function notes(string|null $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Add multiple data to the ticket.
     */
    public function data(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Add a style sheet to the ticket.
     */
    public function addStyleSheet(string $styleSheet): self
    {
        $this->styleSheets[] = $styleSheet;

        return $this;
    }

    /**
     * Set the view name of the ticket.
     */
    public function viewName(string $viewName): self
    {
        $this->viewName = $viewName;

        return $this;
    }
}
