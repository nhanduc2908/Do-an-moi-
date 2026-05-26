import 'package:intl/intl.dart';

extension DateTimeExtension on DateTime {
  String formatDate() {
    return DateFormat('dd/MM/yyyy').format(this);
  }
  
  String formatDateTime() {
    return DateFormat('dd/MM/yyyy HH:mm:ss').format(this);
  }
  
  String formatTime() {
    return DateFormat('HH:mm:ss').format(this);
  }
  
  String formatRelative() {
    final now = DateTime.now();
    final difference = now.difference(this);
    
    if (difference.inDays > 365) return '${(difference.inDays / 365).floor()} năm trước';
    if (difference.inDays > 30) return '${(difference.inDays / 30).floor()} tháng trước';
    if (difference.inDays > 7) return '${(difference.inDays / 7).floor()} tuần trước';
    if (difference.inDays > 0) return '${difference.inDays} ngày trước';
    if (difference.inHours > 0) return '${difference.inHours} giờ trước';
    if (difference.inMinutes > 0) return '${difference.inMinutes} phút trước';
    return 'Vừa xong';
  }
  
  bool isToday() {
    final now = DateTime.now();
    return year == now.year && month == now.month && day == now.day;
  }
  
  bool isYesterday() {
    final yesterday = DateTime.now().subtract(const Duration(days: 1));
    return year == yesterday.year && month == yesterday.month && day == yesterday.day;
  }
  
  DateTime startOfDay() {
    return DateTime(year, month, day);
  }
  
  DateTime endOfDay() {
    return DateTime(year, month, day, 23, 59, 59);
  }
}