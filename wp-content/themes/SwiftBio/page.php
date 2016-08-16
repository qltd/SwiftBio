<?php
/**
* Basic page template
*
* @package _q
*/

get_header();

get_template_part('template-parts/content-page');

if (get_the_title() == 'Development'): ?>

<div class="dev-tech">
    <img src="<?php bloginfo('template_directory'); ?>/img/dev-frameworks.jpg">
    <hr />
    <div class="description">
        <h2>Frameworks</h2>
        <p>Lorem ipsum dolor sit met, consectetur adipiscing elit. Present id imperiet odio. Prior comido lectus a comido ultricies. Quinqué euismod velit sit met leo feugiat, nec posuere mi cursus. Nunc trisomique ut torero vitae tempus. Fusie accumaan ac relit non tempor. Mecenas felis tellus, sollicitudin sit met minibus non, volutpat et rises. Nam est lestés, dictum eu est sed, mollis varius dui. In volutpat erat massa. Suis vel aliquam justo. Vivamus sit met rutrum nibh. Aeneas lagret lectus sed spaden egestas, sed rutrum nibh gravida. Mauris posuere sit smet matris vel pretium. Phasellus in fringilla veilt, vel accumaan turpis. Cras ut ante euismod, fermentum justo vel, congé magna. Morbi convalli non felis sit met efficitur.</p>
    </div>
</div>

<div class="dev-tech">
    <img src="<?php bloginfo('template_directory'); ?>/img/dev-technologies.jpg">
    <hr />
    <div class="description">
        <h2>Technologies</h2>
        <p>Lorem ipsum dolor sit met, consectetur adipiscing elit. Present id imperiet odio. Prior comido lectus a comido ultricies. Quinqué euismod velit sit met leo feugiat, nec posuere mi cursus. Nunc trisomique ut torero vitae tempus. Fusie accumaan ac relit non tempor. Mecenas felis tellus, sollicitudin sit met minibus non, volutpat et rises. Nam est lestés, dictum eu est sed, mollis varius dui. In volutpat erat massa. Suis vel aliquam justo. Vivamus sit met rutrum nibh. Aeneas lagret lectus sed spaden egestas, sed rutrum nibh gravida. Mauris posuere sit smet matris vel pretium. Phasellus in fringilla veilt, vel accumaan turpis. Cras ut ante euismod, fermentum justo vel, congé magna. Morbi convalli non felis sit met efficitur.</p>
    </div>
</div>

<?php endif; ?>

<?php
if (get_the_title() == 'Design' || get_the_title() == 'Branding' || get_the_title() == 'Development'){
       get_template_part('template-parts/client-project-list');
}

get_footer();