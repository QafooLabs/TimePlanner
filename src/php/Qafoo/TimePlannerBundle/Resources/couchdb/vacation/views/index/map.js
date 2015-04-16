function (doc) {
    if (doc.type !== "Qafoo.TimePlannerBundle.Domain.Vacation") {
        return;
    }

    var parseDate = function(date) {
            return new Date(date.substr(0, 4), date.substr(5, 2) - 1, date.substr(8, 2), 12, 0, 0);
        },
        start = parseDate(doc.start);

    emit([start.getFullYear(), start.getMonth() + 1, start.getDate()], 1);
}