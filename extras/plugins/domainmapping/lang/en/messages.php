<?php

return [
    'Domain Mapping' => 'Domain Mapping',
    'Domain Mapping Plugin' => 'Domain Mapping Plugin.',
    'Domain' => 'Domain',
    'Domains' => 'Domains',
    'Host' => 'Host',
    'Hosts' => 'Hosts',
	'Country' => 'Country',
	'Countries' => 'Countries',
    'Base URL' => 'Base URL',
    'country' => 'Country',
    'Purchase Code' => 'Purchase Code',
    'HTTPS' => 'HTTPS',
    'Active' => 'Active',
    'host_example' => 'eg. de.domain.com',
    'host_hint' => 'Enter the country domain (without http:// or https://). <br>Examples:
<ul>
<li><strong>nouveaudomaine.fr</strong> or <strong>fr.domain.com</strong> (France)</li>
<li><strong>neuedomain.de</strong> or <strong>de.domain.com</strong> (Germany)</li>
<li><strong>newdomain.co.uk</strong> or <strong>uk.domain.com</strong> (United Kingdom)</li>
<li><strong>newdomain.in</strong> or <strong>in.domain.com</strong> (India)</li>
<li><strong>novodominio.br</strong> or <strong>br.domain.com</strong> (Brazil)</li>
<li>...</li>
</ul>',
    'Site Name' => 'Site Name',
    'Site Logo' => 'Site Logo',
    'Logo' => 'Logo',
    'supported_file_extensions' => 'Supported file extensions: jpg, jpeg, png, gif',
    'validation' => [
        'country_code' => [
            'required' => 'The :attribute field is required.',
            'min' => 'The :attribute must be at least :min characters.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'unique' => 'The :attribute has already been taken.',
        ],
        'host' => [
            'required' => 'The :attribute field is required.',
            'regex' => 'The :attribute format is invalid.',
            'unique' => 'The :attribute has already been taken.',
        ],
    ],
    'Create Bulk Sub-Domains' => 'Create Bulk Sub-Domains',
    'Create bulk sub-domains based on countries codes.' => 'Create bulk sub-domains based on countries codes.',
    'The sub-domains were been created' => 'The sub-domains were been created.',
    'Sub-domains have already been created' => 'Sub-domains have already been created.',
    'share_session_label' => 'Share session between sub-domains',
    'share_session_hint' => '<ul>
<li>By enabling this option, session will be shared between all the sub-domains of the same domain.</li>
<li>When the session is shared, the domains\' name and logo columns are disabled, <br>the domains admin panels feature is also disabled. <br>So, you have to access to the Admin panel through the main URL that is available in the /.env file (i.e. <code>APP_URL</code>/admin)</li>
<li>When the session is NOT shared, you have to access to the domains Admin panel through their domain name (eg. foo.us/admin, bar.de/admin, toto.fr/admin, ...)</li>
<li>NOTE: By changing this option, the admin user will be log out automatically to prevent any session sharing issue.</li>
</ul>',
	'Settings' => 'Settings',
	'Setting' => 'Setting',
	'Settings of host' => 'Settings of :host',
	'Generate settings entries to customize this domain' => 'Generate settings entries to customize this domain',
	'Generate settings entries' => 'Generate settings entries',
	'Remove the settings & customizations for this domain' => 'Remove the settings & customizations for this domain',
	'Remove the settings' => 'Remove the settings',
	'The settings were been generated successfully for this domain' => 'The settings were been generated successfully for this domain.',
	'No action has been performed' => 'No action has been performed.',
	'Reset all this domain settings' => 'Reset all this domain settings',
	'Use main settings' => 'Use main settings',
	'Use main settings for this domain' => 'Use main settings for this domain',
	'The settings were been reset successfully for this domain' => 'The settings were been reset successfully for this domain.',
	'Homepage' => 'Homepage',
	'Homepage Sections' => 'Homepage Sections',
	'Homepage Section' => 'Homepage Section',
	'Homepage of host' => 'Homepage of :host',
	'Use custom homepage sections for this domain' => 'Use custom homepage sections for this domain',
	'Generate customization entries' => 'Generate customization entries',
	'Remove the homepage sections customization for this domain' => 'Remove the homepage sections customization for this domain',
	'Remove the homepage sections customization' => 'Remove the homepage sections customization',
	'The homepage sections settings were been generated successfully for this domain' => 'The homepage sections settings were been generated successfully for this domain.',
	'The homepage sections settings were been reset successfully for this domain' => 'The homepage sections settings were been reset successfully for this domain.',
	'Meta Tags' => 'Meta Tags',
	'Meta Tag' => 'Meta Tag',
	'Meta tags of host' => 'Meta tags of :host',
	'Generate meta tags entries' => 'Generate meta tags entries',
	'Remove the meta tags' => 'Remove the meta tags',
	'Generate meta tags entries to customize them for this domain' => 'Generate meta tags entries to customize them for this domain',
	'Remove the customized meta tags for this domain' => 'Remove the customized meta tags for this domain',
	'The meta tags entries were been generated successfully for this domain' => 'The meta tags entries were been generated successfully for this domain.',
];
