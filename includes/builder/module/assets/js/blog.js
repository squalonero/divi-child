$(document).ready(function () {
  const $msgWrapper = $("<div>", { class: "skh-blog-message-wrapper" });
  const $shareBtns = $("button.skh-post-meta-share");

  $msgWrapper.prependTo("body");

  $($shareBtns).each((k, v) => {
    $(v).on("click", async (e) => {
      let title = $(v).closest("article").find(".entry-title").text();
      let text = $(v).closest("article").find(".post-content-inner > p").text();
      let url = $(v).closest("article").find("a.more-link").attr("href");

      if (navigator.share) {
        //Mobile
        navigator
          .share({
            title,
            text,
            url,
          })
          .then(() => {})
          .catch((error) => {
            createMsg("error", "Error sharing");
            console.log("Error sharing", error);
          });
      } else {
        //Desktop
        copyText(url);
      }
    });
  });

  const copyText = async (text) => {
    try {
      await navigator.clipboard.writeText(text);
      createMsg("success", SKH_DIVI_CHILD_BLOG.labels.clipboard_text);
    } catch (e) {
      createMsg(
        "error",
        "Share not supported on this browser, do it the old way."
      );
      console.error("Error copying to clipboard", e);
    }
  };

  const createMsg = async (status, msg) => {
    let $wpadminBar = $("#wpadminbar");
    let $msgBox = $("<div>", {
      text: msg,
      class: `skh-blog-message skh-${status}`,
      style: $wpadminBar.length ? "top: 47px" : "",
    });
    await appendToPromise($msgBox, $msgWrapper);
    await $msgBox.fadeIn(500, () => {
      setTimeout(async () => {
        await $msgBox.fadeOut(1000).promise();
        $msgBox.remove();
      }, 1500);
    });
  };

  const appendToPromise = ($el, $target) => {
    return new Promise((res, rej) => {
      $el.appendTo($target);
      res();
    });
  };
});
