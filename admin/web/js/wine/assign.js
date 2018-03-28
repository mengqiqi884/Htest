/**
 * Created by BF on 2016/12/2.
 */
jQuery(document).ready(function () {
    var _opts = {"routes":{
    "avaliable":[
        "\/redactor\/*","\/gridview\/export\/download", "\/gridview\/export\/*","\/gridview\/*", "\/comment\/index","\/comment\/view","\/comment\/create", "\/comment\/update", "\/comment\/delete","\/comment\/*","\/employ\/index", "\/employ\/view", "\/employ\/create","\/employ\/update", "\/employ\/delete", "\/employ\/owners","\/employ\/*","\/good\/index","\/good\/upload","\/good\/view","\/good\/detail","\/good\/create","\/good\/childs","\/good\/update","\/good\/delete","\/good\/*","\/index\/index","\/index\/welcome","\/index\/*","\/manager\/index","\/manager\/list","\/manager\/upload","\/manager\/update","\/manager\/create","\/manager\/lock","\/manager\/del","\/manager\/recover","\/manager\/search","\/manager\/view","\/manager\/*","\/merchant\/index","\/merchant\/view","\/merchant\/valid-form","\/merchant\/create","\/merchant\/update","\/merchant\/delete","\/merchant\/*","\/message\/index","\/message\/view","\/message\/create","\/message\/update","\/message\/delete","\/message\/relation-name","\/message\/*","\/order\/index","\/order\/view","\/order\/create","\/order\/update","\/order\/delete","\/order\/*","\/promotion\/index","\/promotion\/view","\/promotion\/create","\/promotion\/update","\/promotion\/delete","\/promotion\/targets","\/promotion\/*","\/promotion-type\/index","\/promotion-type\/view","\/promotion-type\/create","\/promotion-type\/update","\/promotion-type\/delete","\/promotion-type\/*","\/site\/error","\/site\/index","\/site\/login","\/site\/logout","\/site\/selectcity","\/site\/selectdistrict","\/site\/*","\/valet\/upload"
    ],
    "assigned":[
        "\/*","\/admin\/*","\/admin\/assignment\/*","\/admin\/assignment\/assign","\/admin\/assignment\/index","\/admin\/assignment\/revoke","\/admin\/assignment\/view","\/admin\/default\/*","\/admin\/default\/index","\/admin\/index","\/admin\/menu\/*","\/admin\/menu\/create","\/admin\/menu\/delete","\/admin\/menu\/index","\/admin\/menu\/update","\/admin\/menu\/view","\/admin\/permission\/#","\/admin\/permission\/*","\/admin\/permission\/assign","\/admin\/permission\/create","\/admin\/permission\/delete","\/admin\/permission\/index","\/admin\/permission\/remove","\/admin\/permission\/update","\/admin\/permission\/view","\/admin\/role\/*","\/admin\/role\/assign","\/admin\/role\/create","\/admin\/role\/delete","\/admin\/role\/index","\/admin\/role\/remove","\/admin\/role\/update","\/admin\/role\/view","\/admin\/route\/*","\/admin\/route\/assign","\/admin\/route\/create","\/admin\/route\/index","\/admin\/route\/refresh","\/admin\/route\/remove","\/admin\/rule\/*","\/admin\/rule\/create","\/admin\/rule\/delete","\/admin\/rule\/index","\/admin\/rule\/update","\/admin\/rule\/view","\/admin\/user\/*","\/admin\/user\/activate","\/admin\/user\/change-password","\/admin\/user\/delete","\/admin\/user\/index","\/admin\/user\/login","\/admin\/user\/logout","\/admin\/user\/request-password-reset","\/admin\/user\/reset-password","\/admin\/user\/signup","\/admin\/user\/view","\/banner\/*","\/banner\/create","\/banner\/delete","\/banner\/index","\/banner\/update","\/banner\/view","\/base\/*","\/brand\/*","\/brand\/create","\/brand\/delete","\/brand\/index","\/brand\/update","\/brand\/view","\/car\/*","\/car\/create","\/car\/delete","\/car\/index","\/car\/update","\/car\/view","\/datecontrol\/*","\/datecontrol\/parse\/*","\/datecontrol\/parse\/convert","\/debug\/*","\/debug\/default\/*","\/debug\/default\/db-explain","\/debug\/default\/download-mail","\/debug\/default\/index","\/debug\/default\/toolbar","\/debug\/default\/view","\/gii","\/gii\/#","\/gii\/*","\/gii\/default\/*","\/gii\/default\/action","\/gii\/default\/diff","\/gii\/default\/index","\/gii\/default\/preview","\/gii\/default\/view","\/gii\/model","\/point\/*","\/point\/create","\/point\/delete","\/point\/index","\/point\/update","\/point\/view","\/user\/*","\/user\/create","\/user\/delete","\/user\/delete-img","\/user\/index","\/user\/update","\/user\/view","\/valet\/*","\/valet\/create","\/valet\/delete","\/valet\/index","\/valet\/update","\/valet\/view"
    ]}
};
$('i.glyphicon-refresh-animate').hide();
function updateRoutes(r) {
    _opts.routes.avaliable = r.avaliable;
    _opts.routes.assigned = r.assigned;
    search('avaliable');
    search('assigned');
}

$('#btn-new').click(function () {
    var $this = $(this);
    var route = $('#inp-route').val().trim();
    if (route != '') {
        $this.children('i.glyphicon-refresh-animate').show();
        $.post($this.attr('href'), {route: route}, function (r) {
            $('#inp-route').val('').focus();
            updateRoutes(r);
        }).always(function () {
            $this.children('i.glyphicon-refresh-animate').hide();
        });
    }
    return false;
});

$('.btn-assign').click(function () {
    var $this = $(this);
    var target = $this.data('target');
    var routes = $('select.list[data-target="' + target + '"]').val();

    if (routes && routes.length) {
        $this.children('i.glyphicon-refresh-animate').show();
        $.post($this.attr('href'), {routes: routes}, function (r) {
            updateRoutes(r);
        }).always(function () {
            $this.children('i.glyphicon-refresh-animate').hide();
        });
    }
    return false;
});

$('#btn-refresh').click(function () {
    var $icon = $(this).children('span.glyphicon');
    $icon.addClass('glyphicon-refresh-animate');
    $.post($(this).attr('href'), function (r) {
        updateRoutes(r);
    }).always(function () {
        $icon.removeClass('glyphicon-refresh-animate');
    });
    return false;
});

$('.search[data-target]').keyup(function () {
    search($(this).data('target'));
});

function search(target) {
    var $list = $('select.list[data-target="' + target + '"]');
    $list.html('');
    var q = $('.search[data-target="' + target + '"]').val();
    $.each(_opts.routes[target], function () {
        var r = this;
        if (r.indexOf(q) >= 0) {
            $('<option>').text(r).val(r).appendTo($list);
        }
    });
}

// initial
search('avaliable');
search('assigned');

});