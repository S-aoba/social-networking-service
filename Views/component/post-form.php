<div class="flex">
  <div class="p-5">
    <div class="size-10 rounded-full bg-sky-500/70"></div>
  </div>
  <form action="form/post" method="post" class="w-full flex flex-col space-y-4">
    <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
    <input type="hidden" name="parent_post_id" value="">

    <textarea name="content" id="content" class="w-full resize-none p-2 focus:outline-none" placeholder="いまどうしてる？"></textarea>
    <div class="w-full flex items-center justify-end my-2 pr-2">
      <button type="submit" class="py-2 px-4 rounded-4xl text-sm bg-gray-500/60 text-white font-semibold hover:bg-slate-800 transition duration-300 focus:bg-slate-800">ポストする</button>
    </form>
</div>
</div>