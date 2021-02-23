<?php
/**
* Template Name: Properties
*/
?>

<?php get_header(); ?>

<div class="lds-ring"><div></div><div></div><div></div><div></div></div>

<div id="imre-properties">

	<form id="property-search" @submit.prevent="search()">
		<div class="grid-container">

			<!-- basic search -->
			<div class="properties-filters-wrap">
				<div class="properties-filter">
					<label for="city">CITY</label>
					<filter-select :name="'city'" :collection="cities" :empty="'Select'"></filter-select>
				</div>
				<div class="properties-filter">
					<label for="units">UNITS</label>
					<filter-select :name="'units'" :collection="units" :empty="'Units'"></filter-select>
				</div>
				<div class="properties-filter">
					<label for="units">PRICE</label>
					<filter-select :name="'price'" :collection="prices" :empty="'Price'"></filter-select>
				</div>
			</div>

		</div>
	</form>

	<div class="grid-container">

		<div class="properties-header mt-md mb-md">
			<div v-show="showReset" class="properties-reset-wrap reset-form" @click="resetForm()">
				<span>×</span>Clear Filters
			</div>
			<div v-if="filtered_properties.length" class="mt-sm">
				<!-- <h2 class="section-title blue text-center">{{ propertiesCount !== 0 ? propertiesCount :  filtered_properties.length }} properties</h2> -->
				<!-- <span class="under-bar--centered"></span> -->
			</div>
			<div v-else>
				<h2 class="section-title blue text-center">Sorry, no properties matched your search criteria.</h2>
				<!-- <span class="under-bar--centered"></span> -->
			</div>
		</div>
	</div>

	<!-- grid view -->
	<div class="grid-container xl-container grid-view-wrap">
		<div class="grid-x grid-margin-x grid-margin-y align-center">
			<div class="cell large-4 medium-6 property-card" v-for="property in paginated_properties" v-if="property.status !== 'closed'">
				<div class="property-card__image__wrap">
                    <a :href="property.url">
						<img :src="property.img != null ? property.img.medium || property.img.original : 'https://via.placeholder.com/350'" alt="Property Image" class="property-card__image">
					</a>
				</div>
				<div class="property-card__details">
					<div class="property-card__details__inner__wrap">
						<a :href="property.url">
							<h3 class="property-card__title blue">{{ property.name }}</h3>
						</a>
						<div class="mt-sm mb-sm property-card__details__bottom">
							<div>
								<span class="property-card__city">{{ property.location.city }} </span>
								<span v-if="property.units" class="property-card__units">{{ property.units }} units</span>
							</div>
							<span v-if="property.price.value" class="property-card__price">{{ property.price.value | currency('$', 0) }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="properties-pagination-wrap mt-md mb-xl">
			<pagination :page="page" :per_page="per_page" :total="filtered_properties.length" v-if="filtered_properties.length"></pagination>
		</div>
	</div>
</div>

<?php
	$resources = array();

	foreach (array('properties', 'terms', 'agents') as $v) {
		$resources[$v] = get_option($v.'_cache_file');

		if(@file_get_contents($resources[$v], 0, null, 0, 1) === false) {
			$resources[$v] = null;
		}
		
		if(empty($resources[$v])) {
			$resources[$v] = admin_url('admin-ajax.php').'?action=get_'.$v.'&security='.wp_create_nonce(strrev('imre'));
		}
	}	
?>

<script type="application/javascript">
	var bootstrap = {
		ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
		resource_path: <?php echo json_encode($resources); ?>,
		theme_uri: '<?php echo get_template_directory_uri(); ?>',
		nonce: '<?php echo wp_create_nonce(strrev('imre')); ?>',
		gmaps_key: 'AIzaSyD92C39_T4NEluUc3vO1YLgsj2EZIf7P9o'
	};
    
</script>

<?php get_footer(); ?>