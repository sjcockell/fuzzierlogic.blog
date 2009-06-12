// Ajax Comments
function commentAdded() {
    if ($wptouch('#errors')) {
        $wptouch('#errors').hide();
}
    $wptouch("#commentform").hide();
    $wptouch("#some-new-comment").fadeIn(2000);
    $wptouch("#refresher").fadeIn(2000);
    if ($wptouch('#nocomment')) {
        $wptouch('#nocomment').hide();
    }
    if($wptouch('#hidelist')) {
        $wptouch('#hidelist').hide();
    }
}