import 'package:flutter/material.dart';

class CriteriaCard extends StatelessWidget {
  final String code;
  final String name;
  final int weight;
  final int score;
  final VoidCallback onTap;

  const CriteriaCard({
    super.key,
    required this.code,
    required this.name,
    required this.weight,
    required this.score,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: ListTile(
        leading: Container(
          width: 50,
          height: 50,
          decoration: BoxDecoration(
            color: Colors.blue.shade100,
            borderRadius: BorderRadius.circular(8),
          ),
          child: Center(
            child: Text(code, style: const TextStyle(fontWeight: FontWeight.bold)),
          ),
        ),
        title: Text(name, style: const TextStyle(fontWeight: FontWeight.bold)),
        subtitle: Text('Weight: $weight • Score: $score/5'),
        trailing: const Icon(Icons.chevron_right),
        onTap: onTap,
      ),
    );
  }
}