import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/notification_provider.dart';

class NotificationListScreen extends ConsumerStatefulWidget {
  const NotificationListScreen({super.key});

  @override
  ConsumerState<NotificationListScreen> createState() => _NotificationListScreenState();
}

class _NotificationListScreenState extends ConsumerState<NotificationListScreen> {
  @override
  void initState() {
    super.initState();
    ref.read(notificationProvider.notifier).loadNotifications();
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(notificationProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Notifications'),
        actions: [
          TextButton(
            onPressed: () => ref.read(notificationProvider.notifier).markAllAsRead(),
            child: const Text('Mark all read'),
          ),
        ],
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: state.notifications.length,
              itemBuilder: (context, index) {
                final notification = state.notifications[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 8),
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: notification.isRead ? Colors.grey : Colors.blue,
                      child: const Icon(Icons.notifications, color: Colors.white),
                    ),
                    title: Text(notification.title ?? '', style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Text(notification.message ?? ''),
                    trailing: Text(
                      notification.createdAt?.toLocal().toString().substring(0, 16) ?? '',
                      style: const TextStyle(fontSize: 12, color: Colors.grey),
                    ),
                    onTap: () => ref.read(notificationProvider.notifier).markAsRead(notification.id!),
                  ),
                );
              },
            ),
    );
  }
}