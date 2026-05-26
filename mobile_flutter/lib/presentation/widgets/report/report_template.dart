// Đường dẫn: mobile_flutter/lib/presentation/widgets/report/report_template.dart

import 'package:flutter/material.dart';

class ReportTemplate extends StatelessWidget {
  final String name;
  final String description;
  final String type;
  final VoidCallback onSelect;

  const ReportTemplate({
    super.key,
    required this.name,
    required this.description,
    required this.type,
    required this.onSelect,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: ListTile(
        leading: Container(
          width: 50,
          height: 50,
          decoration: BoxDecoration(
            color: Colors.blue.shade100,
            borderRadius: BorderRadius.circular(8),
          ),
          child: const Icon(Icons.description, color: Colors.blue),
        ),
        title: Text(name, style: const TextStyle(fontWeight: FontWeight.bold)),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(description),
            const SizedBox(height: 4),
            Chip(
              label: Text(type, style: const TextStyle(fontSize: 10)),
              backgroundColor: Colors.grey.shade200,
            ),
          ],
        ),
        trailing: ElevatedButton(
          onPressed: onSelect,
          child: const Text('Use Template'),
        ),
      ),
    );
  }
}