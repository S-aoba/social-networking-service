const trendTab = document.getElementById('trend-tab');
const followerTab = document.getElementById('follower-tab');

trendTab.addEventListener('click', () => {
  document.cookie = 'presentation-tab=trend';

  location.reload();
});

followerTab.addEventListener('click', () => {
  document.cookie = 'presentation-tab=follower';

  location.reload();
});
