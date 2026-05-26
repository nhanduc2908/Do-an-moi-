extension ListExtension<T> on List<T> {
  bool get isNullOrEmpty => this == null || isEmpty;
  bool get isNotNullOrEmpty => this != null && isNotEmpty;
  
  T? firstOrNullWhere(bool Function(T) test) {
    for (final item in this) {
      if (test(item)) return item;
    }
    return null;
  }
  
  List<T> distinctBy(K Function(T) keySelector) {
    final seen = <K>{};
    return where((element) => seen.add(keySelector(element))).toList();
  }
  
  List<List<T>> chunk(int size) {
    if (size <= 0) return [this];
    return [
      for (var i = 0; i < length; i += size)
        sublist(i, i + size > length ? length : i + size)
    ];
  }
  
  Map<K, List<T>> groupBy<K>(K Function(T) keySelector) {
    final map = <K, List<T>>{};
    for (final item in this) {
      final key = keySelector(item);
      map.putIfAbsent(key, () => []).add(item);
    }
    return map;
  }
  
  List<T> sortedBy(Comparable Function(T) keySelector) {
    final list = [...this];
    list.sort((a, b) => keySelector(a).compareTo(keySelector(b)));
    return list;
  }
  
  List<T> sortedByDescending(Comparable Function(T) keySelector) {
    final list = [...this];
    list.sort((a, b) => keySelector(b).compareTo(keySelector(a)));
    return list;
  }
}