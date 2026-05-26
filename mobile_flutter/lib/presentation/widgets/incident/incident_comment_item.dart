// Đường dẫn: mobile_flutter/lib/presentation/widgets/incident/incident_comment_item.dart

import 'package:flutter/material.dart';

class IncidentCommentItem extends StatelessWidget {
  final String userName;
  final String userRole;
  final String comment;
  final DateTime timestamp;
  final String? avatarUrl;

  const IncidentCommentItem({
    super.key,
    required this.userName,
    required this.userRole,
    required this.comment,
    required this.timestamp,
    this.avatarUrl,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          CircleAvatar(
            radius: 20,
            backgroundColor: Colors.blue.shade100,
            child: Text(
              userName.substring(0, 1).toUpperCase(),
              style: const TextStyle(fontWeight: FontWeight.bold),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Text(
                      userName,
                      style: const TextStyle(fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(width: 8),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                      decoration: BoxDecoration(
                        color: Colors.grey.shade200,
                        borderRadius: BorderRadius.circular(4),
                      ),
                      child: Text(
                        userRole,
                        style: const TextStyle(fontSize: 10),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 4),
                Text(comment),
                const SizedBox(height: 4),
                Text(
                  _formatTime(timestamp),
                  style: const TextStyle(fontSize: 10, color: Colors.grey),
                ),
              ],
            ),
          ),
        ],
      ),
    );
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