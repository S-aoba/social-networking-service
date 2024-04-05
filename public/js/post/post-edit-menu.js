const postMenus = document.querySelectorAll('#post-menu');

postMenus.forEach((postMenu) => {
  postMenu.addEventListener('click', () => {
    // クリックされたpost-menu内のmenuを取得
    const menu = postMenu.querySelector('#menu');

    // 表示状態を切り替える
    menu.classList.toggle('hidden');
  });
});
