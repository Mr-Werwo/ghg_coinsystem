
function likeMeme(memeId) {
    fetch('like.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'meme_id=' + memeId
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("likes-" + memeId).innerText = data;
    });
}

function postComment(event, memeId) {
    event.preventDefault();
    let commentText = document.getElementById("comment-text-" + memeId).value;

    fetch('comment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'meme_id=' + memeId + '&comment=' + encodeURIComponent(commentText)
    })
    .then(response => response.text())
    .then(data => {
        let commentsSection = document.getElementById("comments-" + memeId);
        commentsSection.innerHTML += `<p><strong>Du:</strong> ${commentText}</p>`;
        document.getElementById("comment-text-" + memeId).value = '';
    });
}

function likeMeme(memeId) {
    fetch('like.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'meme_id=' + memeId
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes("✔")) {
            document.getElementById("likes-" + memeId).innerText++;
            document.getElementById("like-btn-" + memeId).disabled = true;
        }
        alert(data);
    });
}

function postComment(event, memeId) {
    event.preventDefault();
    let commentText = document.getElementById("comment-text-" + memeId).value;

    fetch('comment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'meme_id=' + memeId + '&comment=' + encodeURIComponent(commentText)
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload();
    });
}

