<div class="grid grid-cols-2 min-h-14 h-14 w-full border-b border-slate-200 font-bold font-sans text-sm">
  <div id="trend-tab" class="col-span-1 h-full flex items-center justify-center transition-colors duration-300 cursor-pointer hover:bg-slate-100">
    <span class="h-full flex items-center px-3 <?= $presentationTab === 'trend' ? 'border-b-4 border-slate-600 relative top-0.5' : 'text-slate-400' ?>">
      トレンド
    </span>
  </div>
  <div id="follower-tab" class="col-span-1 h-full flex items-center justify-center transition-colors duration-300 cursor-pointer hover:bg-slate-100">
    <span class="h-full flex items-center px-3 <?= $presentationTab === 'follower' ? 'border-b-4 border-slate-600 relative top-0.5' : 'text-slate-400' ?>">
      フォロワー
    </span>
  </div>
</div>
