import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/models/notification_model.dart';
import '../../data/repositories/notification_repository.dart';

class NotificationState {
  final List<NotificationModel> notifications;
  final int unreadCount;
  final bool isLoading;
  final String? error;

  NotificationState({
    this.notifications = const [],
    this.unreadCount = 0,
    this.isLoading = false,
    this.error,
  });

  NotificationState copyWith({
    List<NotificationModel>? notifications,
    int? unreadCount,
    bool? isLoading,
    String? error,
  }) {
    return NotificationState(
      notifications: notifications ?? this.notifications,
      unreadCount: unreadCount ?? this.unreadCount,
      isLoading: isLoading ?? this.isLoading,
      error: error ?? this.error,
    );
  }
}

class NotificationNotifier extends StateNotifier<NotificationState> {
  final NotificationRepository _repository;

  NotificationNotifier(this._repository) : super(NotificationState()) {
    loadNotifications();
    loadUnreadCount();
  }

  Future<void> loadNotifications() async {
    state = state.copyWith(isLoading: true, error: null);
    
    final response = await _repository.getNotifications();
    
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(
        notifications: response.data!,
        isLoading: false,
      );
    } else {
      state = state.copyWith(
        isLoading: false,
        error: response.message,
      );
    }
  }

  Future<void> loadUnreadCount() async {
    final response = await _repository.getUnreadCount();
    if (response.isSuccess && response.data != null) {
      state = state.copyWith(unreadCount: response.data);
    }
  }

  Future<bool> markAsRead(String id) async {
    final response = await _repository.markAsRead(id);
    
    if (response.isSuccess) {
      await loadNotifications();
      await loadUnreadCount();
      return true;
    }
    
    state = state.copyWith(error: response.message);
    return false;
  }

  Future<bool> markAllAsRead() async {
    final response = await _repository.markAllAsRead();
    
    if (response.isSuccess) {
      await loadNotifications();
      await loadUnreadCount();
      return true;
    }
    
    state = state.copyWith(error: response.message);
    return false;
  }

  Future<bool> deleteNotification(String id) async {
    final response = await _repository.deleteNotification(id);
    
    if (response.isSuccess) {
      await loadNotifications();
      await loadUnreadCount();
      return true;
    }
    
    state = state.copyWith(error: response.message);
    return false;
  }

  void clearError() {
    state = state.copyWith(error: null);
  }
}

final notificationProvider = StateNotifierProvider<NotificationNotifier, NotificationState>((ref) {
  final repository = NotificationRepository();
  return NotificationNotifier(repository);
});