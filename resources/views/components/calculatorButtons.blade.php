<div class="w-1/4 border-r border-indigo-400 last:border-r-0">
    <button @if(isset($id)) id="{{$id}}" @endif
            class="calculatorBtn w-full h-16 outline-none focus:outline-none hover:bg-indigo-700
            hover:bg-opacity-20 text-white text-xl font-light">{{$value}}</button>
</div>
