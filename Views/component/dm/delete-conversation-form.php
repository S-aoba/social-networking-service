<form 
  action="/form/delete/conversation" 
  method="POST" 
  class="flex flex-col space-y-2 z-50"
>
  <input 
    type="hidden" 
    name="csrf_token" 
    value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>"
  >
  <input 
    type="hidden" 
    name="conversation_id" 
    value="<?= $data['conversation']->getId() ?>"
  >
  
  <div class="w-full space-y-2">
    <p class="text-lg font-sans font-semibold">会話を削除しますか？</p>
    <p class="text-slate-400 text-base/6">この会話はあなたの受信トレイから削除されます。会話に参加している他のアカウントは引き続きこの会話を表示できます。</p>
  </div>
  <div class="py-3 flex flex-col items-center space-y-3">
    <button 
      type="submit" 
      class="w-full rounded-2xl bg-[#EA3323] px-3 py-2 text-sm font-semibold text-white shadow-xs hover:brightness-90 cursor-pointer transition duration-300"
    >
    退出
    </button>
    <button 
      type="button" 
      class="cancel-delete-button w-full rounded-2xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 border border-slate-200 shadow-xs hover:brightness-90 transition duration-300 cursor-pointer"
    >
    キャンセル
    </button>
  </div>
</form>