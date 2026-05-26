// Đường dẫn: mobile_flutter/lib/presentation/widgets/dashboard/recent_activity_card.dart

import 'package:flutter/material.dart';

class RecentActivityCard extends StatelessWidget {
  final List<ActivityItem> activities;

  const RecentActivityCard({super.key, required this.activities});

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Recent Activities',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 12),
            ListView.separated(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: activities.length > 5 ? 5 : activities.length,
              separatorBuilder: (_, __) => const Divider(),
              itemBuilder: (context, index) {
                final activity = activities[index];
                return ListTile(
                  leading: CircleAvatar(
                    backgroundColor: _getActivityColor(activity.type),
                    child: Icon(_getActivityIcon(activity.type), size: 20, color: Colors.white),
                  ),
                  title: Text(activity.title, style: const TextStyle(fontWeight: FontWeight.w500)),
                  subtitle: Text(activity.description),
                  trailing: Text(
                    _formatTime(activity.timestamp),
                    style: const TextStyle(fontSize: 12, color: Colors.grey),
                  ),
                );
              },
            ),
          ],
        ),
      ),
    );
  }

  Color _getActivityColor(String type) {
    switch (type) {
      case 'assessment': return Colors.blue;
      case 'incident': return Colors.red;
      case 'report': return Colors.green;
      default: return Colors.grey;
    }
  }

  IconData _getActivityIcon(String type) {
    switch (type) {
      case 'assessment': return Icons.assessment;
      case 'incident': return Icons.warning;
      case 'report': return Icons.description;
      default: return Icons.notifications;
    }
  }

  String _formatTime(DateTime date) {
    final now = DateTime.now();
    final diff = now.difference(date);
    if (diff.inMinutes < 1) return 'Just now';
    if (diff.inMinutes < 60) return '${diff.inMinutes}m ago';
    if (diff.inHours < 24) return '${diff.inHours}h ago';
    return '${diff.inDays}d ago';
  }
}

class ActivityItem {
  final String title;
  final String description;
  final DateTime timestamp;
  final String type;

  ActivityItem({
    required this.title,
    required this.description,
    required this.timestamp,
    required this.type,
  });
}