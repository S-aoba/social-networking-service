function toggleMenu(menu) {
  // 対応するメニューを取得
  var menuDiv = menu.querySelector('.menu');

  // メニューの表示状態を切り替え
  if (menuDiv.classList.contains('hidden')) {
    // 非表示状態なら表示する
    menuDiv.classList.remove('hidden');
    menuDiv.classList.add('z-20');
  } else {
    // 表示状態なら非表示にする
    menuDiv.classList.add('hidden');
  }
}
const postMenus = document.querySelectorAll('#post-menu');

postMenus.forEach((postMenu) => {
  postMenu.addEventListener('click', () => {
    // クリックされたpost-menu内のmenuを取得
    const menu = postMenu.querySelector('#menu');

    // 表示状態を切り替える
    menu.classList.toggle('hidden');
  });
});
