import Handlebars from 'handlebars';

let titlesAndDescriptions = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        url: Routing.generate('giveTitlesAndDescriptions', { given: 'given', side: 'start' }),
        wildcard: 'given'
    }
});

$('.mb-3 .typeahead').typeahead(null ,
    {
        name: 'tAndD',
        display: 'title',
        source: titlesAndDescriptions,
        templates: {
            suggestion: Handlebars.compile('<div style="background: white; border:1px solid black; border-radius: 10px"><strong>Title: {{ title }}</strong> – {{ price }}$</div>')
        }
    });