<ul class="child-ul">
@foreach($childs as $child)
	<li class="nobranch">
          <input type="checkbox" name="childs[]" value="{{$child->taxonomy_recordid}}"  class="regular-checkbox" @if(in_array($child->taxonomy_parent_name, $parent_taxonomy) || in_array($child->taxonomy_recordid, $child_taxonomy)) checked @endif/> <span class="inputChecked">{{$child->taxonomy_name}}</span>
		@if(count($child->childs))
            @include('layouts.manageChild1',['childs' => $child->childs])
        @endif
	</li>
@endforeach
</ul>