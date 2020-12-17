<style type="text/css">
</style>
<form action="/search_event" method="GET" id="filter_event" class="m-0">
  	<div class="filter-bar container-fluid bg-primary-color home_serach_form filter_serach">
		<div class="container">
			<div class="row">
				<input type="hidden" name="meta_status" id="status" @if(isset($meta_status)) value="{{$meta_status}}" @else value="On" @endif>
				<div class="col-md-5 col-sm-8">
					<div class="form-group text-left form-material m-0" data-plugin="formMaterial">
						<img src="/frontend/assets/images/search.png" alt="" title="" class="form_icon_img">
						<input type="text" class="form-control search-form" name="find" placeholder="Search for Event" id="search_event" @if(isset($chip_event)) value="{{$chip_event}}" @endif>
					</div>
				</div>
				<!-- <div class="col-md-5 col-sm-5">
					<div class="form-group text-left form-material m-0" data-plugin="formMaterial">
						<img src="/frontend/assets/images/location.png" alt="" title="" class="form_icon_img">
						<input type="text" class="form-control pr-50" id="location1" name="search_address" placeholder="Search Location...">
					</div>
				</div> -->
				<div class="col-md-2 col-sm-4">
					<button class="btn btn-raised btn-lg btn_darkblack search_btn" title="Search" style="line-height: 31px;">Search</button>
				</div>
			</div>
		</div>
		{{-- <div class="row">
			<div class="col-md-8 col-sm-8 col-xs-12">
				<div class="row">
		          	<input type="hidden" name="meta_status" id="status" @if(isset($meta_status)) value="{{$meta_status}}" @else value="On" @endif>
					<div class="col-md-4 m-auto">
						<div class="input-search">
							<i class="input-search-icon md-search" aria-hidden="true"></i>
							<input type="text" class="form-control search-form" name="find" placeholder="Search for Event" id="search_event" @if(isset($chip_event)) value="{{$chip_event}}" @endif>
						</div>
					</div>
					@if (Auth::user())
					<div class="col-md-4 m-auto">
						<div class="event-tags-div">
		                    <select class="form-control selectpicker" multiple data-live-search="true" id="event_tag" data-size="3" name="event_tag[]">
		                    	<option value="" selected disabled hidden>Filter by Tags</option>
		                        @foreach($event_tag_list as $key => $event_tag)
		                            <option value="{{$event_tag}}">{{$event_tag}}</option>
		                        @endforeach
		                    </select>
		                </div>
		            </div>
		            @endif
				</div>
			</div>
		</div> --}}
  	</div>
</div>
<style>
/* @media (max-width: 768px){
  .filter-bar{
    display: none;
  }
} */
</style>

<script type="text/javascript">
$(document).ready(function(){
	$('.dropdown-status').click(function(){
		var status = $(this).attr('at');
		var status_meta = $(this).html();
		$("#meta_status").html(status_meta);
		$("#status").val(status);
		$("#filter_event").submit();
	});

    // var tree_data_list = [];

    // var taxonomy_tree = ;
    // var alt_key;
    // var urlParams = new URLSearchParams(window.location.search);
    // var selected_taxonomies = [];
    // if(urlParams.has('selected_taxonomies')) {
    //     selected_taxonomies = urlParams.get('selected_taxonomies').split(',');
    // }


    // if (typeof taxonomy_tree == 'array') {
    //     for (alt_key = 0; alt_key < taxonomy_tree.length; alt_key++) {
    //         var alt_data = {};
    //         var alt_tree = taxonomy_tree[alt_key];
    //         alt_data.text = alt_tree.alt_taxonomy_name;
    //         alt_data.state = {};
    //         alt_data.id = 'alt_' + alt_key;
    //         var alt_tree_parent_taxonomies = [];
    //         if (alt_tree.parent_taxonomies != undefined) {
    //             alt_tree_parent_taxonomies = alt_tree.parent_taxonomies;
    //         }

    //         var parent_data_list = [];
    //         for (parent_key = 0; parent_key < alt_tree_parent_taxonomies.length; parent_key++) {
    //             var parent_tree = alt_tree_parent_taxonomies[parent_key];
    //             var parent_data = {};

    //             if (parent_tree.parent_taxonomy != undefined) {
    //                 if (typeof(parent_tree.parent_taxonomy) == "string") {
    //                     parent_data.text = parent_tree.parent_taxonomy;
    //                     parent_data.id = alt_data.id + '_parent_' + parent_key;
    //                 }
    //                 else {
    //                     parent_data.text = parent_tree.parent_taxonomy.taxonomy_name;
    //                     parent_data.id = alt_data.id + '_child_' + parent_tree.parent_taxonomy.taxonomy_recordid;
    //                     parent_data.state = {};
    //                     if (selected_taxonomies.indexOf(parent_data.id) > -1) {
    //                         parent_data.state.selected = true;
    //                     }
    //                 }
    //                 var parent_tree_child_taxonomies = [];
    //                 if (parent_tree.child_taxonomies != undefined) {
    //                     parent_tree_child_taxonomies = parent_tree.child_taxonomies;
    //                 }
    //                 var child_data_list = [];
    //                 for (child_key = 0; child_key < parent_tree_child_taxonomies.length; child_key++) {
    //                     var child_tree = parent_tree_child_taxonomies[child_key];
    //                     var child_data = {};
    //                     if (child_tree != undefined) {
    //                         child_data.text = child_tree.taxonomy_name;
    //                         child_data.state = {};
    //                         child_data.id = parent_data.id + '_child_' + child_tree.taxonomy_recordid;

    //                         if (selected_taxonomies.indexOf(child_data.id) > -1) {
    //                             child_data.state.selected = true;
    //                         }
    //                         child_data_list.push(child_data);
    //                     }
    //                 }
    //                 if (child_data_list.length != 0) {
    //                     parent_data.children = child_data_list;
    //                 }
    //                 parent_data_list.push(parent_data);
    //             }
    //         }
    //         if (parent_data_list.length != 0) {
    //             alt_data.children = parent_data_list;
    //         }
    //         tree_data_list[alt_key] = alt_data;
    //     }
    // } else {

    //     for (parent_key = 0; parent_key < taxonomy_tree.parent_taxonomies.length; parent_key ++) {
    //         var parent_data = {};
    //         parent_data.id = 'child_' + taxonomy_tree.parent_taxonomies[parent_key].taxonomy_recordid;
    //         parent_data.text = taxonomy_tree.parent_taxonomies[parent_key].taxonomy_name;
    //         parent_data.state = {};
    //         if (selected_taxonomies.indexOf(parent_data.id) > -1) {
    //             parent_data.state.selected = true;
    //         }
    //         // var parent_tree_child_taxonomies = taxonomy_tree.parent_taxonomies[parent_key].child_taxonomies;
    //         // var child_data_list = [];
    //         // for (child_key = 0; child_key < parent_tree_child_taxonomies.length; child_key++) {
    //         //     var child_tree = parent_tree_child_taxonomies[child_key];
    //         //     var child_data = {};
    //         //     if (child_tree != undefined) {
    //         //         child_data.text = child_tree.taxonomy_name;
    //         //         child_data.state = {};
    //         //         child_data.id = parent_data.id + '_child_' + child_tree.taxonomy_recordid;

    //         //         if (selected_taxonomies.indexOf(child_data.id) > -1) {
    //         //             child_data.state.selected = true;
    //         //         }
    //         //         child_data_list.push(child_data);
    //         //     }
    //         // }
    //         // if (child_data_list.length != 0) {
    //         //     parent_data.children = child_data_list;
    //         // }
    //         tree_data_list[parent_key] = parent_data;
    //     }
    // }


    // $('#sidebar_tree').jstree({
    //     'plugins': ["checkbox", "wholerow", "sort"],
    //     'core': {
    //         select_node: 'sidebar_taxonomy_tree',
    //         data: tree_data_list
    //     }
    // });



    $('.download_pdf').on('click', function(e){
        $('#event_pdf').val('pdf');
        $("#filter_event").submit();
        $('#event_pdf').val('');
    });
    $('.download_csv').on('click', function(e){
        $('#event_csv').val('csv');
        $("#filter_event").submit();
        $('#event_csv').val('');
    });
    $('.regular-checkbox').on('click', function(e){
        $(this).prev().trigger('click');
        $('input', $(this).next().next()).prop('checked',0);
        $("#filter_event").submit();
    });
    $('.drop-paginate').on('click', function(){
        $("#paginate").val($(this).text());
        $("#filter_event").submit();
    });
    $('.drop-sort').on('click', function(){
        $("#sort").val($(this).text());
        $("#filter_event").submit();
    });
    let event_tags = "{{ isset($event_tags) ? $event_tags : '' }}"

    if(event_tags == ""){
        event_tags = []
    }else{
        event_tags = event_tags.split(',')
    }

    $('.drop-tags').on('click', function(){
        let text = $(this).text();
        if($.inArray(text,event_tags) == -1){
            event_tags.push(text)
        }else{
            event_tags.splice(event_tags.indexOf(text),1)
        }
        $("#event_tags").val(event_tags);
        $("#filter_event").submit();
    });
    $('#sidebar_tree').on("select_node.jstree deselect_node.jstree", function (e, data) {
        var all_selected_ids = $('#sidebar_tree').jstree("get_checked");
        var selected_taxonomy_ids = all_selected_ids.filter(function(id) {
            return id.indexOf('child_') > -1;
        });
        // console.log(selected_taxonomy_ids);
        selected_taxonomy_ids = selected_taxonomy_ids.toString();
        $("#selected_taxonomies").val(selected_taxonomy_ids);
        $("#filter_event").submit();
    });


});
</script>
