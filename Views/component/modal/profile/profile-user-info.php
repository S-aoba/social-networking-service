<div class="flex flex-col p-5 mt-20 space-y-5">
  <div>
    <label for="username">名前</label>
    <input id="username" name="username" type="text" class="w-full mt-3 p-2 border border-slate-300 rounded-md" value="<?= $profile->getUsername() ?>">
  </div>
  <div>
    <label for="self-introduction">自己紹介</label>
    <textarea name="self-introduction" id="self-introduction" class="w-full mt-3 p-3 border border-slate-300 rounded-md resize-none" value="<?= $profile->getSelfIntroduction() ?>"></textarea>
  </div>
</div>
<div class="flex items-center justify-end pr-5">
  <button id="profileEditCancelBtn" type="button" class="border border-slate-300 py-2 px-3 hover:bg-slate-200 rounded-md transition-colors duration-300">キャンセル</button>
</div>
