import 'package:flutter/material.dart';

class SecurityScoreCard extends StatelessWidget {
  final double score;

  const SecurityScoreCard({super.key, required this.score});

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: Container(
        decoration: BoxDecoration(
          gradient: const LinearGradient(
            colors: [Color(0xFF1A56DB), Color(0xFF1E429F)],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
          borderRadius: BorderRadius.circular(16),
        ),
        child: Padding(
          padding: const EdgeInsets.all(20),
          child: Row(
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Security Score',
                      style: TextStyle(color: Colors.white70, fontSize: 14),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      '${score.toInt()}%',
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 32,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 8),
                    LinearProgressIndicator(
                      value: score / 100,
                      backgroundColor: Colors.white24,
                      color: Colors.white,
                    ),
                  ],
                ),
              ),
              const Icon(Icons.security, size: 48, color: Colors.white),
            ],
          ),
        ),
      ),
    );
  }
}