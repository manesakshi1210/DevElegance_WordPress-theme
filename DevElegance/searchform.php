<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="form-group d-flex align-items-center">
        <label class="sr-only" for="search"></label>
        <input 
            type="search" 
            id="search" 
            class="form-control search-input me-2"
            placeholder="Search…" 
            value="<?php echo get_search_query(); ?>" 
            name="s" 
            required 
        />
        <button type="submit" class="btn btn-primary search-btn">Search</button>
    </div>
</form>
