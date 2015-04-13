function (doc) {
    if (!doc.metaData ||
        !doc.metaData.type ||
        doc.metaData.type != "Qafoo.TimePlannerBundle.Domain.MetaData") {
        return;
    }

    var parseDate = function(date) {
            return new Date(date.substr(0, 4), date.substr(5, 2) - 1, date.substr(8, 2), date.substr(11, 2), date.substr(14, 2), date.substr(17, 2));
        },
        changed = parseDate(doc.metaData.changed);

    emit([doc.type, changed.getFullYear(), changed.getMonth() + 1, changed.getDate(), changed.getHours(), changed.getMinutes(), changed.getSeconds()], 1);
}
