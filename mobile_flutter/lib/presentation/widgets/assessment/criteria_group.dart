// Đường dẫn: mobile_flutter/lib/presentation/widgets/assessment/criteria_group.dart

import 'package:flutter/material.dart';

class CriteriaGroup extends StatelessWidget {
  final String domainName;
  final List<CriteriaItem> items;
  final Function(int) onItemTap;

  const CriteriaGroup({
    super.key,
    required this.domainName,
    required this.items,
    required this.onItemTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      child: ExpansionTile(
        leading: CircleAvatar(
          backgroundColor: Colors.blue.shade100,
          child: Text(
            domainName.substring(0, 1),
            style: const TextStyle(fontWeight: FontWeight.bold),
          ),
        ),
        title: Text(
          domainName,
          style: const TextStyle(fontWeight: FontWeight.bold),
        ),
        subtitle: Text('${items.length} criteria'),
        children: [
          ListView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: items.length,
            itemBuilder: (context, index) {
              final item = items[index];
              return ListTile(
                leading: Icon(
                  item.isCompleted ? Icons.check_circle : Icons.radio_button_unchecked,
                  color: item.isCompleted ? Colors.green : Colors.grey,
                ),
                title: Text(item.name),
                subtitle: Text('Weight: ${item.weight} • Score: ${item.score}/${item.maxScore}'),
                trailing: const Icon(Icons.chevron_right),
                onTap: () => onItemTap(index),
              );
            },
          ),
        ],
      ),
    );
  }
}

class CriteriaItem {
  final String id;
  final String name;
  final int weight;
  final int score;
  final int maxScore;
  final bool isCompleted;

  CriteriaItem({
    required this.id,
    required this.name,
    required this.weight,
    required this.score,
    required this.maxScore,
    this.isCompleted = false,
  });
}