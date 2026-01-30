<div class="drawer-side lg:fixed lg:top-0 lg:left-0 lg:h-screen lg:z-0">
  <label for="my-drawer" class="drawer-overlay"></label>
  <ul class="menu p-4 w-80 min-h-full text-base-content bg-blue-800 flex-col">
    <!--Beneficiary Logo + Name (logo is temporary)-->
    <div class="flex items-center p-4 border-b border-blue-700">
      <x-heroicon-s-building-office-2 class="size-18 text-yellow-400 mr-2"/>
      <span class="ml-3 font-bold text-yellow-400 text-lg md:text-xl">Master Coating Industrial Technology Incorporated</span>
    </div>
    <li>
      <h1 class = "menu-title text-base text-white px-4 py-2 rounded">Menu</h1>
      <ul>
        @can("view dashboard")
        <li><x-navlinks routeName="dashboard.index" title="Dashboard">
          <x-heroicon-s-home class="size-5 mr-2" />
        </x-navlinks></li>
        @endcan
        @can("view assets")
        <li>
          <x-navlinks routeName="assets.index" title="Assets">
            <x-heroicon-s-cube class="w-5 h-5 mr-2" />
          </x-navlinks>
        </li>
        @endcan
        @can("view requests")
        <li>
          <x-navlinks routeName="requests.index" title="Requests">
             <x-heroicon-s-clipboard-document-list class="size-5 mr-2" />
          </x-navlinks>
        </li>
        @endcan
        @can("view workorders")
        <li>
          <x-navlinks routeName="workorders.index" title="Workorders">
            <x-heroicon-s-clipboard-document class="size-5 mr-2" />
          </x-navlinks>
        </li>
        @endcan
        @can("view employees")
        <li><x-navlinks routeName="employees.index" title="Employees">
          <x-heroicon-s-user-group class="size-5 mr-2"/>
        </x-navlinks></li>
        @endcan
        @can("view reports")
          <li>
            <details>
              <x-dropdown-navs title="Reports">
                <x-heroicon-c-chart-bar-square class="size-5 mr-2" />
              </x-dropdown-navs>
              <ul>
                <li>
                  <x-navlinks routeName="placeholder" title="Asset Reports" >
                    <x-heroicon-s-table-cells class="size-5 mr-2" />
                  </x-navlinks>
                  <x-navlinks routeName="placeholder" title="Depreciation Reports">
                    <x-heroicon-s-calculator class="size-5 mr-2" />
                  </x-navlinks>
                  <x-navlinks routeName="placeholder" title="Request History Reports">
                    <x-heroicon-c-document-chart-bar class="size-5 mr-2" />
                  </x-navlinks>
                  <x-navlinks routeName="placeholder" title="Asset Service Reports">
                    <x-heroicon-s-wrench class="size-5 mr-2" />
                  </x-navlinks>
                </li>
              </ul>
            </details>
          </li>
        @endcan
      </ul>
    </li>
    <li>
      <h1 class = "menu-title text-base text-white px-4 py-2 rounded">General</h1>
      <ul>
        <!--System Users-->
        @can("view users")
        <li><x-navlinks routeName="users.index" title="Users">
          <x-heroicon-s-user class="size-5 mr-2"/>
        </x-navlinks></li>
        @endcan
        @can("view configs")
        <li>
          {{-- if any of the routes here gets chosen the config drowdown stays open --}}
          <details {{ request()->is('configs*') ? 'open' : '' }}>
            <x-dropdown-navs title="Configurations">
              <x-heroicon-o-adjustments-horizontal class="size-5 mr-2" />
            </x-dropdown-navs>
            <ul>
              @can("view departments")
              <li><x-navlinks routeName="department.index" title="Departments">
                <x-heroicon-s-briefcase class="size-5 mr-2"/>
              </x-navlinks></li>
              @endcan
              @can("view categories")
              <li>
                <x-navlinks routeName="category.index" title="Categories">
                  <x-heroicon-c-square-2-stack class="w-5 h-5 mr-2" />
                </x-navlinks>
              </li>
              @endcan
              @can("view sub-categories")
              <li>
                <x-navlinks routeName="subcategory.index" title="Subcategories">
                  <x-heroicon-s-folder-open class="w-5 h-5 mr-2" />
                </x-navlinks>
              </li>
              @endcan
              @can("view suppliers")
              <li>
                <x-navlinks routeName="suppliers.index" title="Suppliers">
                  <x-heroicon-s-truck class="w-5 h-5 mr-2" />
                </x-navlinks>
              </li>
              @endcan
            </ul>
          </details>
        </li>
        @endcan
        <!--Logout-->
        <li>
          <form class="w-full group hover:bg-yellow-700/20" method = "POST" action="{{ route("logoutUser") }}">
            @csrf
            <button 
              type="submit" 
              class="flex items-center group-hover:text-yellow-400 rounded text-base text-white w-full text-left appearance-none bg-transparent border-none cursor-pointer p-1"
            >
              <x-heroicon-o-arrow-left-on-rectangle class="size-5 mr-4.5 mt-0.5" />
              Logout
            </button>
          </form>
        </li>
      </ul>
    </li>
  </ul>
</div>