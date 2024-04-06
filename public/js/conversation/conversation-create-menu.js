const conversationMenuIcons = document.querySelectorAll('#conversation-menu-icon');

conversationMenuIcons.forEach((icon) => {
  icon.addEventListener('click', () => {
    const conversationMenu = icon.querySelector('#conversation-menu');

    conversationMenu.classList.toggle('hidden');
  });
});
