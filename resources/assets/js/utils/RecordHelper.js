export default {
  filterRecords(records, key, int = true) {
    return records.map(function(record){
      return int ? parseInt(record[key]) : record[key];
    })
  }
}