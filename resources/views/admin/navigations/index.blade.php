@extends('admin.layouts.app')
@section('content')

<div class="row">
    <div class="row g-3 flex-between-end mb-5" style="padding-top:0px !important">
            <div class="col-auto"> 
              <h2 class="mb-2">Điều hướng website</h2>
              
            </div> 
            <div class="col-auto">
              <button type="button" class="btn btn-primary mb-2 mb-sm-0 navigation-create-trigger">Thêm điều hướng</button>
            </div>
    </div>   


    <div class="card col-xl-12" > 
        
       <div class="card-body">
            <div class="mb-4">
              
            </div>
            <div class=" border-top border-bottom border-translucent position-relative top-1">
              <div class="table-responsive scrollbar-overlay mx-n1 px-1">
                <table id="navigation_table" class="table table-sm fs-9 mb-0">
                  <thead>
                    <tr>
                      <th class="white-space-nowrap fs-9 align-middle ps-0">
                        Vị trí
                      </th>
                      <th class="sort align-middle pe-5" scope="col" data-sort="customer" style="width:70%;">Tiêu đề</th>
                      <th class="sort align-middle pe-5" scope="col" data-sort="customer" style="width:70%;">Đến</th>
                      <th class="sort align-middle pe-5" scope="col" data-sort="email" style="width:20%;">Id</th>           
                      <th class="sort align-middle text-end pe-3" scope="col"  style="min-width:100px">tác vụ</th>
                      
                    </tr>
                  </thead>
                  <tbody class="list" id="customers-table-body">

                    <?php 
                    foreach ($navigations as $navigation) 
                    {
                        ?>

                    <tr navigation_id="{{$navigation['id']}}" class="parent hover-actions-trigger btn-reveal-trigger position-static">
                      <td class="align-middle actions   pe-3" style="min-width:100px"> 
                      
                      <button data-bs-toggle="tooltip" data-bs-title="Xuống"  class="parent down btn btn-link text-body-quaternary p-0 me-2">
                        <span class="text" data-feather="arrow-down" style="height: 15px; width: 15px;"></span>
                      </button>
                      <button data-bs-toggle="tooltip" data-bs-title="Lên"  class="parent up btn btn-link text-body-quaternary p-0 me-2">
                        <span class="text" data-feather="arrow-up" style="height: 15px; width: 15px;"></span>
                      </button>
                        
                       
                        




                      </td>
                      <td class="customer align-middle white-space-nowrap pe-5">
                        <a  target="_blank" class="d-flex align-items-center text-body-emphasis" href="<?php echo getRoutingUrl($navigation['routing_id']); ?>">
                          <p class="mb-0 ms-3 text-body-emphasis fw-bold"><?php echo $navigation['name']; ?></p>
                        </a></td>
                        <td class="email align-middle white-space-nowrap pe-5"><?php echo getRoutingTitle($navigation['routing_id']); ?></td>
                      <td class="email align-middle white-space-nowrap pe-5"><?php echo $navigation['id']; ?></td>
                      
                      <td class="align-middle actions  text-end pe-3">
                        <button type="button" data-bs-toggle="tooltip" data-bs-title="Thêm menu thuộc menu này" data-parent-id="{{$navigation['id']}}" class="btn btn-link text-body-quaternary p-0 me-2 navigation-create-trigger">
                         <span class="fas fa-plus text-body"></span>
                        </button>
                        <button type="button" data-navigation-id="{{$navigation['id']}}" class="btn btn-link text-body-quaternary p-0 me-2 navigation-edit-trigger">
                         <span class="fas fa-edit text-body"></span>
                        </button>
                        <button data-bs-toggle="offcanvas" data-bs-target="#offcanvas_<?php echo $navigation['id']; ?>" aria-controls="offcanvas_<?php echo $navigation['id']; ?>" class="btn btn-link text-body-quaternary p-0 text-danger"> 
                          <span class="fa-solid fa-trash text-danger"></span> 
                        </button>                 
                          <div class="offcanvas offcanvas-end" id="offcanvas_<?php echo $navigation['id']; ?>" tabindex="-1" aria-labelledby="offcanvas_<?php echo $navigation['id']; ?>Label">
                           <div class="offcanvas-body " style="padding-top: 100px;" >
                             {{$navigation['name']}}
                            </div>
                            <div class="offcanvas-body bottom" style="position: absolute;bottom: 0;width: 100%;">
                              Xóa bài viết này?
                              <div class="mt-3">
                                <form method="POST" action="/admin/navigations/<?php echo $navigation['id']; ?>">
                                  @csrf
                                  @method('DELETE')
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Hủy</button>
                                  <button type="submit" class="btn btn-danger">Xóa bài viết</button>
                                </form>
                              </div>
                            </div>
                          </div>

                        </td>
                    </tr>
                    <?php
                    if(count($navigation['children'])>0)
                    {
                    ?>
                    <?php 
                              foreach($navigation['children'] as $index=>$children)
                              {
                              ?>
                        <tr navigation_id="{{$children->id}}"  class="child">
                              
                              
                                <td class="align-middle actions  text-end pe-3" style="min-width:100px" >     
                                  <button data-bs-toggle="tooltip"   class=" btn btn-link text-body-quaternary p-0 me-2">
                                    <span class="text" data-feather="minus" style="height: 15px; width: 15px;"></span>
                                  </button>   
                                  <button data-bs-toggle="tooltip" data-bs-title="Xuống"  class="down btn btn-link text-body-quaternary p-0 me-2">
                                    <span class="text" data-feather="arrow-down" style="height: 15px; width: 15px;"></span>
                                  </button>
                                  <button data-bs-toggle="tooltip" data-bs-title="Lên"  class="up btn btn-link text-body-quaternary p-0 me-2">
                                    <span class="text" data-feather="arrow-up" style="height: 15px; width: 15px;"></span>
                                  </button>
                                </td>
                                <td class="customer align-middle white-space-nowrap pe-5" style="width:70%;"><p class="mb-0 ms-3 text-body-emphasis fw-bold"><a target="_blank" class="d-flex align-items-center text-body-emphasis" href="<?php echo getRoutingUrl($children->routing_id); ?>"><?php echo $children->name; ?></a></p></td>
                                <td class="email align-middle white-space-nowrap pe-5" style="width:20%;"><?php echo getRoutingTitle($children->routing_id); ?></td>
                                <td class="email align-middle white-space-nowrap pe-5" style="width:20%;"><?php echo $children->id; ?></td>
                                <td class="align-middle actions  text-end pe-3" style="min-width:100px">


                                  <button type="button" data-navigation-id="{{$children->id}}" class="btn btn-link text-body-quaternary p-0 me-2 navigation-edit-trigger">
                                  <span class="fas fa-edit text-body"></span>
                                  </button>
                                  <button data-bs-toggle="offcanvas" data-bs-target="#offcanvas_<?php echo $children->id; ?>" aria-controls="offcanvas_<?php echo $children->id; ?>" class="btn btn-link text-body-quaternary p-0 text-danger"> 
                                    <span class="fa-solid fa-trash text-danger"></span> 
                                  </button>                 
                                    <div class="offcanvas offcanvas-end" id="offcanvas_<?php echo $children->id; ?>" tabindex="-1" aria-labelledby="offcanvas_<?php echo $children->id; ?>Label">
                                    <div class="offcanvas-body " style="padding-top: 100px;" >
                                      {{$children->name}}   
                                      </div>
                                      <div class="offcanvas-body bottom" style="position: absolute;bottom: 0;width: 100%;">
                                        Xóa điều hướng {{$children->name}}?
                                        <div class="mt-3">
                                          <form method="POST" action="/admin/navigations/{{$children->id}}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Hủy</button>
                                            <button type="submit" class="btn btn-danger">Xóa bài viết</button>
                                          </form>
                                        </div>
                                      </div>
                                    </div>


                                </td>
                                </tr>
                              <?php
                              }
                              ?>
                            
                        
                        <?php
                      }
                      ?>
                    <?php
                    }
                    ?>

                  </tbody>
                </table>
              </div>
             
            </div>
          </div>


    </div>



</div>

<div class="modal fade" id="navigation_create_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="navigation_create_form" action="/admin/navigations" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Thêm điều hướng</h5>
          <button type="button" class="btn btn-close p-1" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-floating mb-3">
            <input class="form-control" name="name" id="navigation_create_name" type="text" placeholder="Tên">
            <label for="navigation_create_name">Tên</label>
            <div class="invalid-feedback"></div>
          </div>

          <div class="form-floating mb-3">
            <select name="parent_id" class="form-select" id="navigation_create_parent_id">
              <option value="">Cấp 1</option>
              <?php foreach ($parents as $parent) { ?>
              <option value="{{$parent->id}}">{{$parent->name}}</option>
              <?php } ?>
            </select>
            <label for="navigation_create_parent_id">Menu cha</label>
            <div class="invalid-feedback"></div>
          </div>

          <div class="form-floating form-floating-advance-select">
            <select name="routing_id" class="form-select" id="navigation_create_routing_id" data-choices="data-choices" data-options='{"removeItemButton":true,"placeholder":true}'>
              <option value=''>Chọn nội dung</option>
              <?php foreach ($routings as $routing) { ?>
              <option value="<?php echo $routing->id;?>"><?php echo ($routing->entity=='pages' ? '[Trang] ' : '[Bài viết] ').($routing->title ?: $routing->slug);?></option>
              <?php } ?>
            </select>
            <label for="navigation_create_routing_id">Đến (Trang / Bài viết)</label>
            <div class="invalid-feedback"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-phoenix-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Thêm điều hướng</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="navigation_edit_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="navigation_edit_form" method="POST">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <div class="modal-header">
          <h5 class="modal-title">Sửa điều hướng</h5>
          <button type="button" class="btn btn-close p-1" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-floating mb-3">
            <input class="form-control" name="name" id="navigation_edit_name" type="text" placeholder="Tên">
            <label for="navigation_edit_name">Tên</label>
            <div class="invalid-feedback"></div>
          </div>

          <div class="form-floating mb-3">
            <select name="parent_id" class="form-select" id="navigation_edit_parent_id">
              <option value="">Cấp 1</option>
              <?php foreach ($parents as $parent) { ?>
              <option value="{{$parent->id}}">{{$parent->name}}</option>
              <?php } ?>
            </select>
            <label for="navigation_edit_parent_id">Menu cha</label>
            <div class="invalid-feedback"></div>
          </div>

          <div class="form-floating form-floating-advance-select">
            <select name="routing_id" class="form-select" id="navigation_edit_routing_id">
              <option value=''>Chọn nội dung</option>
              <?php foreach ($routings as $routing) { ?>
              <option value="<?php echo $routing->id;?>"><?php echo ($routing->entity=='pages' ? '[Trang] ' : '[Bài viết] ').($routing->title ?: $routing->slug);?></option>
              <?php } ?>
            </select>
            <label for="navigation_edit_routing_id">Đến (Trang / Bài viết)</label>
            <div class="invalid-feedback"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-phoenix-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
#navigation_table tr td {padding:1rem 0rem}
.child {background-color:rgba(203, 208, 221, 0.15)}
</style>
@endsection


@section('js')
<script type="text/javascript">
$(document).on('click', '.parent.up', function () {
      let parent = $(this).closest('tr.parent');
    let children = parent.nextUntil('tr.parent');

    let prevParent = parent.prevAll('tr.parent').first();
    if (!prevParent.length) 
      {
        update_order();
        return;
      }
    parent.add(children).insertBefore(prevParent);
    update_order(); 
});
 
$(document).on('click', '.parent.down', function () {
    let parent = $(this).closest('tr.parent');
    let children = parent.nextUntil('tr.parent');

    let nextParent = parent.nextAll('tr.parent').first();
    
    if (!nextParent.length) 
      {
        update_order();
        return;
      }
    // Get next parent's children
    let nextChildren = nextParent.nextUntil('tr.parent');

    if (nextChildren.length) {
        // Insert after last child of next parent
        parent.add(children).insertAfter(nextChildren.last());
    } else {
        // Insert directly after next parent
        parent.add(children).insertAfter(nextParent);
    }
    update_order();
});



$(document).on('click', '.child .up', function () {
    let row = $(this).closest('tr.child');
    let prev = row.prev('tr.child');

    // Stop if previous row is not a child (i.e. parent)
    if (!prev.length) return;

    prev.before(row);
    update_order();
});

$(document).on('click', '.child .down', function () {
    let row = $(this).closest('tr.child');
    let next = row.next('tr.child');

    // Stop if next row is not a child
    if (!next.length) return;

    next.after(row);
    update_order();
});


let order = [];
function update_order(){
  order = [];
    $('#navigation_table tr').each(function (index) {
      if($(this).attr('navigation_id'))
      {
        order.push({ 
            id: $(this).attr('navigation_id'), 
            sort: index + 1
        });  
      }
    });
 
    
 

    $.ajax({
    url: '/admin/navigations/sort',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({"sort":order}),
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});




}

function navigation_show_field_errors($form, errors) {
    $.each(errors, function (field, msgs) {
        var $field = $form.find("[name='" + field + "']");
        $field.addClass('is-invalid');
        $field.closest('.form-floating, .form-floating-advance-select').find('.invalid-feedback').html(msgs[0]);
    });
}

// Create modal
$(document).on('click', '.navigation-create-trigger', function (e) {
    e.preventDefault();
    var parentId = $(this).data('parent-id') || '';

    $('#navigation_create_form')[0].reset();
    $('#navigation_create_form .invalid-feedback').html('');
    $('#navigation_create_form .form-control, #navigation_create_form .form-select').removeClass('is-invalid');
    $('#navigation_create_parent_id').val(parentId);

    $('#navigation_create_modal').modal('show');
});

$('#navigation_create_form').on('submit', function (e) {
    e.preventDefault();
    var $form = $(this);
    $form.find('.invalid-feedback').html('');
    $form.find('.form-control, .form-select').removeClass('is-invalid');

    $.ajax({
        url: $form.attr('action'),
        type: 'POST',
        data: $form.serialize(),
        success: function () {
            window.location.reload();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                navigation_show_field_errors($form, xhr.responseJSON.errors);
            }
        }
    });
});

// Edit modal
var navigationEditRoutingChoices = new Choices('#navigation_edit_routing_id', {
    itemSelectText: '',
    allowHTML: true,
    removeItemButton: true,
    placeholder: true
});

$(document).on('click', '.navigation-edit-trigger', function (e) {
    e.preventDefault();
    var navigationId = $(this).data('navigation-id');

    $.getJSON('/admin/navigations/' + navigationId + '/edit', function (res) {
        var $form = $('#navigation_edit_form');
        $form.attr('action', '/admin/navigations/' + res.id);
        $form.find('.invalid-feedback').html('');
        $form.find('.form-control, .form-select').removeClass('is-invalid');

        $('#navigation_edit_name').val(res.name);
        $('#navigation_edit_parent_id').val(res.parent_id || '');

        navigationEditRoutingChoices.removeActiveItems();
        if (res.routing_id) {
            navigationEditRoutingChoices.setChoiceByValue(String(res.routing_id));
        }

        $('#navigation_edit_modal').modal('show');
    });
});

$('#navigation_edit_form').on('submit', function (e) {
    e.preventDefault();
    var $form = $(this);
    $form.find('.invalid-feedback').html('');
    $form.find('.form-control, .form-select').removeClass('is-invalid');

    $.ajax({
        url: $form.attr('action'),
        type: 'POST',
        data: $form.serialize(),
        success: function () {
            window.location.reload();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                navigation_show_field_errors($form, xhr.responseJSON.errors);
            }
        }
    });
});
</script>

@endsection