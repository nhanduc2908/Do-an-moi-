// Đường dẫn: mobile_flutter/test/unit/date_helper_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/core/utils/date_helper.dart';

void main() {
  group('DateHelper Tests', () {
    final testDate = DateTime(2024, 1, 15, 10, 30, 0);

    test('formatDateTime returns correct format', () {
      final result = DateHelper.formatDateTime(testDate);
      
      expect(result, '15/01/2024 10:30:00');
    });

    test('formatDate returns correct format', () {
      final result = DateHelper.formatDate(testDate);
      
      expect(result, '15/01/2024');
    });

    test('formatTime returns correct format', () {
      final result = DateHelper.formatTime(testDate);
      
      expect(result, '10:30:00');
    });

    test('formatRelative returns "Just now" for recent time', () {
      final now = DateTime.now();
      final result = DateHelper.formatRelative(now);
      
      expect(result, 'Just now');
    });

    test('formatRelative returns minutes ago', () {
      final past = DateTime.now().subtract(const Duration(minutes: 5));
      final result = DateHelper.formatRelative(past);
      
      expect(result, '5 minutes ago');
    });

    test('formatRelative returns hours ago', () {
      final past = DateTime.now().subtract(const Duration(hours: 2));
      final result = DateHelper.formatRelative(past);
      
      expect(result, '2 hours ago');
    });

    test('formatRelative returns days ago', () {
      final past = DateTime.now().subtract(const Duration(days: 3));
      final result = DateHelper.formatRelative(past);
      
      expect(result, '3 days ago');
    });

    test('startOfDay returns beginning of day', () {
      final result = DateHelper.startOfDay(testDate);
      
      expect(result.hour, 0);
      expect(result.minute, 0);
      expect(result.second, 0);
    });

    test('endOfDay returns end of day', () {
      final result = DateHelper.endOfDay(testDate);
      
      expect(result.hour, 23);
      expect(result.minute, 59);
      expect(result.second, 59);
    });

    test('isToday returns true for today', () {
      final today = DateTime.now();
      final result = DateHelper.isToday(today);
      
      expect(result, true);
    });

    test('isToday returns false for other date', () {
      final result = DateHelper.isToday(testDate);
      
      expect(result, false);
    });

    test('isSameDay returns true for same day', () {
      final date1 = DateTime(2024, 1, 15);
      final date2 = DateTime(2024, 1, 15);
      final result = DateHelper.isSameDay(date1, date2);
      
      expect(result, true);
    });

    test('isSameDay returns false for different days', () {
      final date1 = DateTime(2024, 1, 15);
      final date2 = DateTime(2024, 1, 16);
      final result = DateHelper.isSameDay(date1, date2);
      
      expect(result, false);
    });
  });
}