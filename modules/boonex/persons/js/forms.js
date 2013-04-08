
function bx_persons_action_with_ghost (sAction, iFileId, iContentId, oUploader) {
    bx_loading("bx-uploader-file-" + iFileId, true);
    $.post(sUrlRoot + "modules/?r=persons/" + sAction + "/" + iFileId + "/" + iContentId, function(s) {
        bx_loading("bx-uploader-file-" + iFileId, false);
        if (s.length)
            alert(s);
        else
            oUploader.restoreGhosts();
    });
}

function bx_persons_discard_ghost (iFileId, iContentId, oUploader) {
    bx_persons_action_with_ghost ('discard_ghost', iFileId, iContentId, oUploader);
}

function bx_persons_delete_ghost (iFileId, iContentId, oUploader) {
    bx_persons_action_with_ghost ('delete_ghost', iFileId, iContentId, oUploader);
}

function bx_persons_toggle_draft (iFileId, iCurrentFileId) {
    if (iFileId == iCurrentFileId) {
        $(".bx-uploader-ghost-current").show();
        $(".bx-uploader-ghost-draft").hide();
    } else {
        $(".bx-uploader-ghost-current").hide();
        $(".bx-uploader-ghost-draft").show();
    }
}

