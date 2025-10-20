window.toggleChildVisibility = (element, selector = '.toggle-class', visible = true) => {
    element.querySelectorAll(selector).forEach((elmnt) => {
        if (visible) {
            elmnt.classList.remove('d-none');
        } else {
            elmnt.classList.add('d-none');
        }
    });
}
