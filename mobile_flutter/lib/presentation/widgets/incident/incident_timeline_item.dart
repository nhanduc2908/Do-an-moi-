// Đường dẫn: mobile_flutter/lib/presentation/widgets/incident/incident_timeline_item.dart

import 'package:flutter/material.dart';

class IncidentTimelineItem extends StatelessWidget {
  final String title;
  final String description;
  final DateTime time;
  final String type;
  final bool isLast;

  const IncidentTimelineItem({
    super.key,
    required this.title,
    required this.description,
    required this.time,
    required this.type,
    this.isLast = false,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SizedBox(
          width: 40,
          child: Column(
            children: [
              Container(
                width: 12,
                height: 12,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: _getTypeColor(),
                ),
              ),
              if (!isLast)
                Container(
                  width: 2,
                  height: 60,
                  color: Colors.grey.shade300,
                ),
            ],
          ),
        ),
        Expanded(
          child: Padding(
            padding: const EdgeInsets.only(bottom: 16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 4),
                Text(
                  description,
                  style: const TextStyle(fontSize: 12, color: Colors.grey),
                ),
                const SizedBox(height: 4),
                Text(
                  _formatTime(time),
                  style: const TextStyle(fontSize: 10, color: Colors.grey),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Color _getTypeColor() {
    switch (type) {
      case 'creation': return Colors.blue;
      case 'assignment': return Colors.purple;
      case 'investigation': return Colors.orange;
      case 'resolution': return Colors.green;
      default: return Colors.grey;
    }
  }

  String _formatTime(DateTime date) {
    return '${date.day}/${date.month}/${date.year} ${date.hour}:${date.minute.toString().padLeft(2, '0')}';
  }
}