import 'package:intl/intl.dart';

class DateHelper {
  static String formatDateTime(DateTime dateTime) {
    return DateFormat('dd/MM/yyyy HH:mm:ss').format(dateTime);
  }
  
  static String formatDate(DateTime date) {
    return DateFormat('dd/MM/yyyy').format(date);
  }
  
  static String formatTime(DateTime time) {
    return DateFormat('HH:mm:ss').format(time);
  }
  
  static String formatRelative(DateTime dateTime) {
    final now = DateTime.now();
    final difference = now.difference(dateTime);
    
    if (difference.inDays > 365) return '${(difference.inDays / 365).floor()} years ago';
    if (difference.inDays > 30) return '${(difference.inDays / 30).floor()} months ago';
    if (difference.inDays > 7) return '${(difference.inDays / 7).floor()} weeks ago';
    if (difference.inDays > 0) return '${difference.inDays} days ago';
    if (difference.inHours > 0) return '${difference.inHours} hours ago';
    if (difference.inMinutes > 0) return '${difference.inMinutes} minutes ago';
    return 'Just now';
  }
  
  static String formatShortDate(DateTime date) {
    return DateFormat('dd/MM/yy').format(date);
  }
  
  static String formatMonthYear(DateTime date) {
    return DateFormat('MM/yyyy').format(date);
  }
  
  static String formatDayMonth(DateTime date) {
    return DateFormat('dd/MM').format(date);
  }
  
  static DateTime startOfDay(DateTime date) {
    return DateTime(date.year, date.month, date.day);
  }
  
  static DateTime endOfDay(DateTime date) {
    return DateTime(date.year, date.month, date.day, 23, 59, 59);
  }
  
  static DateTime startOfWeek(DateTime date) {
    final difference = (date.weekday - DateTime.monday).abs();
    return date.subtract(Duration(days: difference));
  }
  
  static DateTime endOfWeek(DateTime date) {
    return startOfWeek(date).add(const Duration(days: 6));
  }
  
  static DateTime startOfMonth(DateTime date) {
    return DateTime(date.year, date.month, 1);
  }
  
  static DateTime endOfMonth(DateTime date) {
    return DateTime(date.year, date.month + 1, 0);
  }
  
  static bool isToday(DateTime date) {
    final now = DateTime.now();
    return date.year == now.year && date.month == now.month && date.day == now.day;
  }
  
  static bool isYesterday(DateTime date) {
    final yesterday = DateTime.now().subtract(const Duration(days: 1));
    return date.year == yesterday.year && date.month == yesterday.month && date.day == yesterday.day;
  }
  
  static bool isSameDay(DateTime date1, DateTime date2) {
    return date1.year == date2.year && date1.month == date2.month && date1.day == date2.day;
  }
}