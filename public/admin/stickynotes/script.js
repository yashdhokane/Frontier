$(document).ready(function() {

    $(document).on('click', '#showStickyNote', function() {
        $('.stickyMainSection').show();
    });

    $(document).on('click', '#close-task-detail', function() {
        $('.stickyMainSection').hide();
        $('.stickyNotesList').show();
        $('.addStickyNote').hide();
    });

    $(document).on('click', '.addStickyNoteBtn', function() {
        $('.stickyNotesList').hide();
        $('.addStickyNote').show();
    });

    $(document).on('click', '.closeStickyAdd', function() {
        $('.stickyNotesList').show();
        $('.addStickyNote').hide();
    });

});