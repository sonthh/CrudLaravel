
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="collapse navbar-collapse" id="">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('home') ? 'active' : '' }}" href="#">Home</a>
            </li>
            <li class="nav-item {{ request()->is('admin/article/*') ? 'active' : '' }}">
                <a class="nav-link"
                   href="/admin/article/index">Article</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Category</a>
            </li>

        </ul>
    </div>
</nav>