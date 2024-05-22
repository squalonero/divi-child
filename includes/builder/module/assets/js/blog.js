$(document).ready(function () {
  const $shareBtns = $("button.skh-post-meta-share");

  $($shareBtns).each((k, v) => {
    $(v).on("click", (e) => {
      let title = $(v).closest("article").find(".entry-title").text();
      let text = $(v).closest("article").find(".post-content-inner > p").text();
      let url = $(v).closest("article").find("a.more-link").attr("href");

      console.log(title, text, url);

      if (navigator.share) {
        navigator
          .share({
            title,
            text,
            url,
          })
          .then(() => console.log("Successful share"))
          .catch((error) => console.log("Error sharing", error));
      } else {
        navigator.clipboard.writeText(url)
        console.log("Share not supported on this browser, do it the old way.");
      }
    });
  });
});
