const replyMenuIcons = document.querySelectorAll('#reply-menu-icon');

replyMenuIcons.forEach((replyMenuIcon) => {
  replyMenuIcon.addEventListener('click', () => {
    // クリックされたpost-menu内のmenuを取得
    const replyMenu = replyMenuIcon.querySelector('#reply-menu');

    // 表示状態を切り替える
    replyMenu.classList.toggle('hidden');
  });
});
