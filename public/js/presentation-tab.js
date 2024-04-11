const trendTab = document.getElementById('trend-tab');
const followerTab = document.getElementById('follower-tab');

if (trendTab) {
  trendTab.addEventListener('click', () => {
    document.cookie = 'presentation-tab=trend';

    location.reload();
  });
}

if (followerTab) {
  followerTab.addEventListener('click', () => {
    document.cookie = 'presentation-tab=follower';

    location.reload();
  });
}
