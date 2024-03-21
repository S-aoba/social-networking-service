<div class="bg-gray-100 font-sans">
  <div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <!-- Conversation List -->
      <div class="col-span-1 md:col-span-3">
        <div class="bg-white rounded-lg shadow-md overflow-y-auto max-h-screen">
          <ul class="divide-y divide-gray-200">
            <?php foreach ($data_list as $data) : ?>
              <!-- Conversation Item -->
              <li class="px-6 py-4 transition duration-300 ease-in-out hover:bg-gray-100 hover:cursor-pointer">
                <a href="/message/<?= $data['conversation']->getConversationId() ?>">
                  <div class="flex items-center">
                    <!-- 相手のアイコン -->
                    <img class="w-12 h-12 rounded-full mr-4 border border-gray-400" src="<?= $data['other_user_profile_image_path'] ?>" alt="Sender">
                    <div class="w-full flex flex-col space-y-3">
                      <div class="flex justify-between">
                        <div class="w-full flex space-x-2 items-center">
                          <h3 class="text-lg font-semibold text-gray-800"><?= $data['other_user_name'] ?></h3>
                          <span class="text-sm text-gray-400 mr-1">@<span><?= $data['other_user_id'] ?></span></span>
                          <!-- TODO: 更新されたらupdated_atを表示するようににする -->
                          <span class="text-sm text-gray-400"><?= $data['conversation']->getDataTimeStamp()->getCreatedAt() ?></span>
                        </div>
                        <form action="#" method="POST" id="deleteConversationForm">
                          <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
                          <input type="hidden" name="conversation_id" value="<?= $data['conversation']->getConversationId() ?>">
                          <button type="submit" class="border border-red-500 px-2 py-1 rounded bg-red-500 text-sm text-white hover:bg-red-600 transition duration-300 ease-in-out">削除</button>
                        </form>
                      </div>
                      <?php if (count($data['message']) >= 1) : ?>
                        <p class="text-sm text-gray-600"><?= $data['message'][0]->getMessageBody() ?></p>
                      <?php else : ?>
                        <p class="text-sm text-gray-600">まだメッセージはありません。</p>
                      <?php endif; ?>
                    </div>
                  </div>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <!-- Messages -->
      <div class="col-span-2">
        <div class="max-w-md bg-white rounded-lg shadow-md overflow-hidden">
          <!-- Header -->
          <div class="bg-gray-100 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800">メッセージを選択</h3>
          </div>
          <div class="flex mt-4">
            <button id="newMessageButton" class="px-2 py-2 bg-blue-500 text-white rounded-lg m-3 text-sm hover:bg-blue-700">新しいメッセージ</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="myModal" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex justify-center items-center">
  <!-- Modal Content -->
  <div class="bg-white p-8 rounded-lg">
    <h2 class="text-2xl font-semibold mb-6">新しいメッセージを送信</h2>
    <div class="w-full h-full">
      <?php foreach ($followee_users as $followee_user) : ?>
        <form method="POST" class="newConversationForm">
          <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
          <input type="hidden" name="participant1_id" value="<?= $_SESSION['user_id'] ?>">
          <input type="hidden" name="participant2_id" value="<?= $followee_user->getUserId() ?>">
          <button type="submit" class="w-full flex items-center z-10 hover:cursor-pointer hover:bg-gray-200 p-2">
            <img class="w-12 h-12 rounded-full mr-4 border border-gray-400" src="<?= $followee_user->getProfileImage() ?>" alt="フォロワー">
            <div>
              <p class="text-base text-gray-500"><?= $followee_user->getUsername() ?></p>
              <p class="text-base text-gray-500"><?= $followee_user->getUserId() ?></p>
            </div>
          </button>
          <hr class="border-gray-300 mb-6 mt-3">
        </form>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
  const modal = document.getElementById('myModal');
  const newMessageButton = document.getElementById('newMessageButton');

  newMessageButton.onclick = function() {
    modal.classList.remove('hidden');
  };


  window.onclick = function(event) {
    if (event.target == modal) {
      modal.classList.add('hidden');
    }
  };

  const newConversationForms = document.querySelectorAll('.newConversationForm');

  newConversationForms.forEach(newConversationForm => {
    newConversationForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const formData = new FormData(newConversationForm);

      fetch('/form/conversation', {
        method: 'POST',
        body: formData
      }).then((res) => {
        res.json().then((data) => {
          if (data.status === 'success') {
            // TODO: モーダルを閉じる
            modal.classList.add('hidden');

            // TODO:受け取ったデータを使って画面を遷移させる
            location.href = '/message/' + data.conversation_id;
          }
        })
      })

    });

  });


  const deleteConversationForms = document.querySelectorAll('#deleteConversationForm');

  deleteConversationForms.forEach(deleteConversationForm => {

    deleteConversationForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const formData = new FormData(deleteConversationForm);

      fetch('/form/conversation/delete', {
        method: 'POST',
        body: formData
      }).then((res) => {
        res.json().then((data) => {
          if (data.status === 'success') {
            setTimeout(() => {
              location.reload();
            }, 1000);
          }
        })
      })

    });
  });
</script>
