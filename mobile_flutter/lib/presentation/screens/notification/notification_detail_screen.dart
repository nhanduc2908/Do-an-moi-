import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/notification_provider.dart';

class NotificationDetailScreen extends ConsumerStatefulWidget {
  const NotificationDetailScreen({super.key, required this.notificationId});
  final String notificationId;

  @override
  ConsumerState<NotificationDetailScreen> createState() => _NotificationDetailScreenState();
}

class _NotificationDetailScreenState extends ConsumerState<NotificationDetailScreen> {
  @override
  void initState() {
    super.initState();
    ref.read(notificationProvider.notifier).markAsRead(widget.notificationId);
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(notificationProvider);
    final notification = state.notifications.firstWhere(
      (n) => n.id == widget.notificationId,
      orElse: () => throw Exception('Notification not found'),
    );

    return Scaffold(
      appBar: AppBar(
        title: const Text('Notification Details'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      notification.title ?? '',
                      style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      notification.message ?? '',
                      style: const TextStyle(fontSize: 16),
                    ),
                    const SizedBox(height: 16),
                    Text(
                      'Received: ${notification.createdAt?.toLocal().toString()}',
                      style: const TextStyle(color: Colors.grey),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}