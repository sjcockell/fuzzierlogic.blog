// http://www.quirksmode.org/js/selected.html
function sociable_get_selection() {
    if (window.getSelection)
        return "" + window.getSelection();
    else if (document.getSelection)
        return "" + document.getSelection();
    else if (document.selection)
        return "" + document.selection.createRange().text;
}

function sociable_description_link(link, attribute) {
    if (typeof(link.original_link) == "undefined")
        link.original_link = link.href;
    link.href = link.original_link + "&" + attribute + "=" + sociable_get_selection();
    return false;
}
