<aside id="layout-menu" class="layout-menu sidebar menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="<?= base_url(ADMINPATH . 'dashboard') ?>" class="app-brand-link">
      <a href="javascript:void(0);" class="layout-menu-toggle d-xl-none toggle-btn menu-link text-large ms-auto">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
      </a>
      <span class="ddd">
        <img src="<?=$logo?>" class="dlogo" >
        <!--<h5><?= !empty($company['company_name']) ? $company['company_name'] : "" ?></h5>-->
      </span>
      <span class="app-brand-text demo menu-text fw-bold ms-2"></span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>
  <?php
  $session = session();
  $username = '';
  $role = '';
  if ($session->has('login_data')) {
    $loginData = $session->get('login_data');

    if (isset($loginData['role_user_name'])) {
      $username = $loginData['role_user_name'];
    }
    if (isset($loginData['role'])) {
      $role = $loginData['role'];
    }
  }
  ?>
  <div class="menu-inner-shadow"></div>
  
  <?php
  $read_menuArray = [];
  $write_menuArray = [];
  if ($loginData['role'] == "Admin") {
    $read_menuArray = explode(',', ($loginData['read_menu_ids']));
    $write_menuArray = explode(',', ($loginData['write_menu_ids']));
  }
  $menuList = getData("menus", 'id,menu_title,slug,menu_type,menu_id', ['status' => 'Active'],null,null,'priority DESC');
//   echo "<pre>";
//   print_r($menuList);exit;
  ?>
  <ul class="menu-inner py-1">
    <?php
    $url = getUri(2) ?? 'dashboard';
    ?>
    <?php
    if ($loginData['role'] == "Admin") {
      if (!empty($menuList)) {
        foreach ($menuList as $key => $value) {
          if ($value['menu_type'] == 'Menu') {
            if ((in_array($value['id'], $read_menuArray)) || (in_array($value['id'], $write_menuArray))) { ?>
              <!-- Main Menu Item -->
              <li class="menu-item <?= $url == $value['slug'] ? "active" : "" ?>">
                <a href="<?= !empty($value['slug']) ? base_url(ADMINPATH) . $value['slug'] : '#'; ?>" class="menu-link <?= !empty($value['slug']) &&  $value['slug']=="#" ? "menu-toggle" : ''; ?>">
                  <i class="menu-icon tf-icons bx bx-layout"></i>
                  <div data-i18n="<?= $value['menu_title']; ?>"><?= $value['menu_title']; ?></div>
                </a>
                <!-- Submenu Items -->
                <?php $subMenuList = getSubMenuList($menuList, $value['id']);
                if (!empty($subMenuList)) { ?>
                  <ul class="menu-sub">
                    <?php foreach ($subMenuList as $key => $value1) { ?>
                      <li class="menu-item">
                        <a href="<?= !empty($value1['slug']) ? base_url(ADMINPATH) . $value1['slug'] : '#'; ?>" class="menu-link <?= $url == $value1['slug'] ? "active" : ""; ?> <?=($value1['slug']=="view-teacher-detail")?"d-none":""?>" >
                          <div data-i18n="<?= $value1['menu_title']; ?>"><?= $value1['menu_title']; ?></div>
                        </a>
                      </li>
                    <?php } ?>
                  </ul>
                <?php } ?>
              </li>
            <?php }
          }
        }
      }
    } else { ?>
      <!-- Generating Menu Items for Non-Admin Users -->
      <?php
      if (!empty($menuList)) {
        foreach ($menuList as $key => $value) {
          if ($value['menu_type'] == 'Menu') { ?>
            <li class="menu-item  <?= $url == $value['slug'] ? "active" : "" ?>">
              <a href="<?= !empty($value['slug']) ? base_url(ADMINPATH) . $value['slug'] : '#'; ?>" class="menu-link <?= !empty($value['slug']) &&  $value['slug']=="#" ? "menu-toggle" : ''; ?>">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="<?= $value['menu_title']; ?>"><?= $value['menu_title']; ?></div>
              </a>
              <!-- Submenu Items -->
              <?php $subMenuList = getSubMenuList($menuList, $value['id']);
              if (!empty($subMenuList)) { ?>
                <ul class="menu-sub">
                  <?php foreach ($subMenuList as $key => $value1) { ?>
                    <li class="menu-item <?= $url == $value1['slug'] ? "active" : "" ?>">
                      <a href="<?= !empty($value1['slug']) ? base_url(ADMINPATH) . $value1['slug'] : '#'; ?>" class="menu-link <?= $url == $value1['slug'] ? "active" : ""; ?> <?=($value1['slug']=="view-teacher-detail")?"d-none":""?>">
                        <div data-i18n="<?= $value1['menu_title']; ?>"><?= $value1['menu_title']; ?></div>
                      </a>
                    </li>
                  <?php } ?>
                </ul>
              <?php } ?>
            </li>
      <?php }
        }
      }
    } ?>
  </ul>
</aside>
