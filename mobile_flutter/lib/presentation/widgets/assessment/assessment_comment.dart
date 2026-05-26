// Đường dẫn: mobile_flutter/lib/presentation/widgets/assessment/assessment_comment.dart

import 'package:flutter/material.dart';

class AssessmentComment extends StatelessWidget {
  final String userName;
  final String comment;
  final DateTime timestamp;
  final String? avatar;

  const AssessmentComment({
    super.key,
    required this.userName,
    required this.comment,
    required this.timestamp,
    this.avatar,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          CircleAvatar(
            radius: 16,
            backgroundColor: Colors.blue.shade100,
            child: Text(
              avatar ?? userName.substring(0, 1).toUpperCase(),
              style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold),
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
                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                    ),
                    const SizedBox(width: 8),
                    Text(
                      _formatTime(timestamp),
                      style: const TextStyle(fontSize: 10, color: Colors.grey),
                    ),
                  ],
                ),
                const SizedBox(height: 4),
                Text(
                  comment,
                  style: const TextStyle(fontSize: 13),
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