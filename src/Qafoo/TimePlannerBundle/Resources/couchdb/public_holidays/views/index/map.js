function (doc) {
    if (doc.type !== "Qafoo.TimePlannerBundle.Domain.PublicHoliday") {
        return;
    }

    var parseDate = function(date) {
            return new Date(date.substr(0, 4), date.substr(5, 2) - 1, date.substr(8, 2), 12, 0, 0);
        },
        date = parseDate(doc.date);

    emit([date.getFullYear(), date.getMonth() + 1, date.getDate()], 1);
}
