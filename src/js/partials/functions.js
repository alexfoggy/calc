function openActiveElement(selector) {
    $('#fixed-overlay').addClass('active');
    $('html').addClass('no_scroll');
    $('body').addClass('no_scroll');
    $(selector).addClass('active');
    scrollLock(selector);
};

function closeActiveElement() {
    $('#fixed-overlay').removeClass('active');
    $('html').removeClass('no_scroll');
    $('body').removeClass('no_scroll');
    bodyScrollLock.clearAllBodyScrollLocks();
};

function scrollLock(selector) {
    return bodyScrollLock.disableBodyScroll(document.querySelector(selector));
}; // iOS blocking body scroll function.

function openTab(evt, tabName) {
    $('.tab, .tabs-content').removeClass('active');
    $('#' + tabName).addClass('active');
    $(evt.currentTarget).addClass('active');
}