<form 
  method="POST" 
  class="delete-post-form flex flex-col space-y-2 z-50"
>
  <input 
    type="hidden" 
    name="csrf_token" 
    value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>"
  >
  <input 
    type="hidden" 
    name="post_id" 
    value="<?= $data['post']->getId() ?>"
  >
  <input 
    type="hidden" 
    name="author_id" 
    value="<?= $data['post']->getUserId() ?>"
  >
  <div class="w-full space-y-2">
    <div class="delete-post-error-message hidden py-2 text-center text-red-600 bg-red-100 rounded-lg"></div>

    <p class="text-lg font-sans font-semibold">ポストを削除しますか？</p>
    <p class="text-slate-400 text-base/6">この操作は取り消せません。プロフィール、あなたをフォローしているアカウントのタイムライン、検索結果からポストが削除されます。</p>
  </div>
  <div class="py-3 flex flex-col items-center space-y-3">
    <button 
      type="submit" 
      class="w-full rounded-2xl bg-[#EA3323] px-3 py-2 text-sm font-semibold text-white shadow-xs hover:brightness-90 cursor-pointer transition duration-300"
    >
    削除
    </button>
    <button 
      type="button" 
      class="cancel-delete-button w-full rounded-2xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 border border-slate-200 shadow-xs hover:brightness-90 transition duration-300 cursor-pointer"
    >
    キャンセル
    </button>
  </div>
</form>