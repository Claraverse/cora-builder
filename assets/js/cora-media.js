jQuery(document).ready(function ($) {
  // --- REPEATER LOGIC ---
  $(document).on("click", ".add-repeater-row", function () {
    const $wrap = $(this).closest(".cora-repeater-wrap");
    const $rowsWrap = $wrap.find(".cora-repeater-rows");
    const $lastRow = $rowsWrap.find(".repeater-row").last();
    const $newRow = $lastRow.clone();

    const nextIndex = $rowsWrap.find(".repeater-row").length;

    $newRow.find("input, textarea, select").each(function () {
      let name = $(this).attr("name");
      if (name) {
        // Find existing [index] and replace it with [nextIndex]
        $(this).attr("name", name.replace(/\[\d+\]/, "[" + nextIndex + "]"));
      }
      $(this).val("");
    });

    $rowsWrap.append($newRow);
  });

  $(document).on("click", ".remove-repeater-row", function () {
    const $rowsWrap = $(this).closest(".cora-repeater-rows");
    if ($rowsWrap.find(".repeater-row").length > 1) {
      $(this).closest(".repeater-row").remove();
    }
  });

  // --- MEDIA LIBRARY LOGIC ---
  $(document).on("click", ".cora-media-upload-btn", function (e) {
    e.preventDefault();
    const $btn = $(this);
    const $wrap = $btn.closest(".cora-media-upload-wrap");
    const isGallery = $wrap.data("type") === "gallery";

    const frame = wp.media({
      title: isGallery ? "Build Gallery" : "Select Media",
      button: { text: isGallery ? "Add to Gallery" : "Use Media" },
      multiple: isGallery ? "add" : false,
    });

    frame.on("select", function () {
      const selection = frame.state().get("selection");
      if (isGallery) {
        let urls = $wrap.find(".cora-media-input").val()
          ? $wrap.find(".cora-media-input").val().split(",")
          : [];
        let html = "";
        selection.map(function (attachment) {
          const data = attachment.toJSON();
          urls.push(data.url);
          html += `<div class="gallery-item" style="width:85px; height:85px; position:relative; border-radius:8px; overflow:hidden; border:1px solid #cbd5e1;"><img src="${data.url}" style="width:100%; height:100%; object-fit:cover;"><span class="dashicons dashicons-no-alt remove-gal-img" style="position:absolute; top:4px; right:4px; background:#fff; color:#ef4444; cursor:pointer; border-radius:50%; width:18px; height:18px; font-size:16px; display:flex; align-items:center; justify-content:center;"></span></div>`;
        });
        $wrap.find(".cora-media-input").val(urls.join(","));
        $wrap.find(".cora-gallery-preview").append(html);
      } else {
        const attachment = selection.first().toJSON();
        $wrap.find(".cora-media-input").val(attachment.url);
        if ($wrap.find(".cora-media-preview").length) {
          $wrap
            .find(".cora-media-preview")
            .html(
              `<img src="${attachment.url}" style="max-width:100%; max-height:100%;">`
            );
        }
      }
    });
    frame.open();
  });

  $(document).on("click", ".remove-gal-img", function () {
    const $item = $(this).closest(".gallery-item");
    const $wrap = $(this).closest(".cora-media-upload-wrap");
    $item.remove();
    let urls = [];
    $wrap.find(".gallery-item img").each(function () {
      urls.push($(this).attr("src"));
    });
    $wrap.find(".cora-media-input").val(urls.join(","));
  });
});
