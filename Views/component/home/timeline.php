<div class="col-span-8 w-full h-full flex flex-col overflow-auto">
  <?php include "Views/component/home/tab.php" ?>
  <?php include "Views/component/post-form.php" ?>

  <?php if($postsCount > 0): ?>
    <div class="divide-y divide-slate-200">
      <?php foreach ($posts as $data): ?>
        <?php include "Views/component/article.php" ?>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="pt-10 text-center font-semibold text-lg">
      投稿はありません
    </div>
  <?php endif; ?>
   
</div>

<script src="/js/like.js"></script>
<script src="/js/open-delete-post-menu.js"></script>
<script src="/js/open-delete-post-modal.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const deletePostForms = document.querySelectorAll('.delete-post-form');

    deletePostForms.forEach((form) => {
      form.addEventListener('submit', async(e) => {
        e.preventDefault();
  
        const formData = new FormData(form);

        const errorMessage = document.querySelector('.delete-post-error-message');
        errorMessage.textContent = '';
        errorMessage.classList.add('hidden');
  
        const res = await fetch('api/delete/post', {
          method: 'POST',
          body: formData
        });
  
        const data = await res.json();
  
        if(data.status === 'success') {
          window.location.reload();
        }
        else {
          errorMessage.textContent = data.message;
          errorMessage.classList.remove('hidden');
        }
      })
    })
  })
</script>