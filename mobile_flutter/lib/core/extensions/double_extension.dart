extension DoubleExtension on double {
  String toPercent() {
    return '${toStringAsFixed(0)}%';
  }
  
  String toCurrency({String symbol = '₫'}) {
    final formatter = NumberFormat('#,###');
    return '${formatter.format(this)} $symbol';
  }
  
  String toFileSize() {
    if (this < 1024) return '${toStringAsFixed(0)} B';
    if (this < 1024 * 1024) return '${(this / 1024).toStringAsFixed(1)} KB';
    if (this < 1024 * 1024 * 1024) return '${(this / (1024 * 1024)).toStringAsFixed(1)} MB';
    return '${(this / (1024 * 1024 * 1024)).toStringAsFixed(1)} GB';
  }
  
  String toScore({int maxScore = 100}) {
    final score = (this / maxScore * 100).toStringAsFixed(0);
    return '$score/$maxScore';
  }
  
  String toRating() {
    if (this >= 4.5) return 'Xuất sắc';
    if (this >= 4.0) return 'Tốt';
    if (this >= 3.0) return 'Khá';
    if (this >= 2.0) return 'Trung bình';
    return 'Kém';
  }
  
  String toRiskLevel() {
    if (this >= 8) return 'Nghiêm trọng';
    if (this >= 6) return 'Cao';
    if (this >= 4) return 'Trung bình';
    return 'Thấp';
  }
  
  Color toRiskColor() {
    if (this >= 8) return Colors.red;
    if (this >= 6) return Colors.orange;
    if (this >= 4) return Colors.yellow;
    return Colors.green;
  }
}